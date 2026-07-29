<?php

namespace GeneralPurposeIO\I2C\Drivers;

use Microscrap\Bindings\MPSSE\MPSSE;
use Microscrap\Bindings\MPSSE\MPSSEContext;
use GeneralPurposeIO\Contracts\I2C\I2CException;
use Fabricate\NutsAndBolts\Concerns\Splices16Bits;

class UsbI2CDriver extends I2CDriver
{
    use Splices16Bits;

    public function __construct(
        protected readonly MPSSEContext $context,
    ) {}

    public function probe(int $address): bool
    {
        if ($address < 0x03 || $address > 0x77) {
            return false;
        }

        MPSSE::start($this->context);
        $acknowledged = $this->writeByte(($address << 1) | 0);
        MPSSE::stop($this->context);

        return $acknowledged;
    }

    public function writeRead(int $address, array|string $bytes_to_write, int $bytes_to_read): array|false
    {
        if (is_array($bytes_to_write)) {
            $bytes_to_write = array2bytes($bytes_to_write);
        }

        MPSSE::start($this->context);

        $wrote = $this->writeByte(($address << 1) | 0)
            && $this->clockOut($bytes_to_write);

        if (! $wrote) {
            MPSSE::stop($this->context);

            return false;
        }

        MPSSE::start($this->context); // repeated START

        if (! $this->writeByte(($address << 1) | 1)) {
            MPSSE::stop($this->context);

            return false;
        }

        $data = $this->clockIn($bytes_to_read);

        MPSSE::stop($this->context);

        return is_null($data) ? false : bytes2array($data);
    }

    public function bulkWrite(int $address, array|string $messages): array|false
    {
        $chunks = static::normalizeBulkMessages($messages);
        if (count($chunks) === 0) {
            return [];
        }

        MPSSE::start($this->context);

        $acknowledged = $this->writeByte(($address << 1) | 0);
        foreach ($chunks as $chunk) {
            $acknowledged = $acknowledged && $this->clockOut($chunk);
        }

        MPSSE::stop($this->context);

        return $acknowledged ? array_map('strlen', $chunks) : false;
    }

    /**
     * @throws I2CException
     */
    public function read(int $address, int $len): array|false
    {
        if (!(($address > 0x07) && ($address <= 0x77))) {
            throw I2CException::invalidSlaveAddress($address);
        }

        MPSSE::start($this->context);

        $addrByte = ($address << 1) | 1;
        $addrOk = $this->writeByte($addrByte);

        if (! $addrOk) {
            MPSSE::stop($this->context);

            // #region agent log
            file_put_contents('/Users/angelgonzalez/Development/PHP/PSS/ScrapyardIO/scrapyard-io/.cursor/debug-5016b5.log', json_encode(['sessionId' => '5016b5', 'runId' => 'post-fix', 'hypothesisId' => 'L', 'location' => 'UsbI2CDriver.php:read', 'message' => 'USB I2C read address NACK', 'data' => ['address' => sprintf('0x%02X', $address), 'len' => $len, 'addrByte' => sprintf('0x%02X', $addrByte)], 'timestamp' => (int) (microtime(true) * 1000)]).PHP_EOL, FILE_APPEND);
            // #endregion

            return false;
        }

        $data = $this->clockIn($len);

        MPSSE::stop($this->context);

        $result = is_null($data) ? false : bytes2array($data);

        // #region agent log
        file_put_contents('/Users/angelgonzalez/Development/PHP/PSS/ScrapyardIO/scrapyard-io/.cursor/debug-5016b5.log', json_encode(['sessionId' => '5016b5', 'runId' => 'post-fix', 'hypothesisId' => 'L', 'location' => 'UsbI2CDriver.php:read', 'message' => 'USB I2C read result', 'data' => ['address' => sprintf('0x%02X', $address), 'len' => $len, 'ok' => $result !== false, 'got' => $result === false ? null : count($result)], 'timestamp' => (int) (microtime(true) * 1000)]).PHP_EOL, FILE_APPEND);
        // #endregion

        return $result;
    }

    public function write(int $address, array|string $data): int
    {
        if (is_array($data)) {
            $data = array2bytes($data);
        }

        MPSSE::start($this->context);

        $addrByte = ($address << 1) | 0;
        $addrWriteRc = MPSSE::write($this->context, chr($this->getLowByte($addrByte)));
        $addrAck = $addrWriteRc === 0 ? MPSSE::getAck($this->context) : null;
        $addrOk = $addrWriteRc === 0 && $addrAck === 0;

        $byteAcks = [];
        $dataOk = $addrOk;
        if ($addrOk) {
            $len = strlen($data);
            for ($i = 0; $i < $len; $i++) {
                $byte = ord($data[$i]);
                $rc = MPSSE::write($this->context, chr($this->getLowByte($byte)));
                $ack = $rc === 0 ? MPSSE::getAck($this->context) : null;
                $ok = $rc === 0 && $ack === 0;
                $byteAcks[] = [
                    'index' => $i,
                    'byte' => sprintf('0x%02X', $byte),
                    'writeRc' => $rc,
                    'ack' => $ack,
                    'ok' => $ok,
                ];
                if (! $ok) {
                    $dataOk = false;
                    break;
                }
            }
        }

        MPSSE::stop($this->context);

        // #region agent log
        file_put_contents('/Users/angelgonzalez/Development/PHP/PSS/ScrapyardIO/scrapyard-io/.cursor/debug-5016b5.log', json_encode(['sessionId' => '5016b5', 'hypothesisId' => 'A,B,E', 'location' => 'UsbI2CDriver.php:write', 'message' => 'USB I2C write ACK breakdown', 'data' => ['address' => sprintf('0x%02X', $address), 'payloadHex' => bin2hex($data), 'payloadLen' => strlen($data), 'addrWriteRc' => $addrWriteRc, 'addrAck' => $addrAck, 'addrOk' => $addrOk, 'byteAcks' => $byteAcks, 'result' => $dataOk ? strlen($data) : -1], 'timestamp' => (int) (microtime(true) * 1000)]).PHP_EOL, FILE_APPEND);
        // #endregion

        return $dataOk ? strlen($data) : -1;
    }

    public function close(): void
    {
        mpsse_close($this->context);
    }

    public function getContext(): MPSSEContext
    {
        return $this->context;
    }

    private function writeByte(int $byte): bool
    {
        if (MPSSE::write($this->context, chr($this->getLowByte($byte))) !== 0) {
            return false;
        }

        return MPSSE::getAck($this->context) === 0;
    }

    private function clockOut(string $data): bool
    {
        $len = strlen($data);

        for ($i = 0; $i < $len; $i++) {
            if (! $this->writeByte(ord($data[$i]))) {
                return false;
            }
        }

        return true;
    }

    private function clockIn(int $len): ?string
    {
        if ($len <= 0) {
            return '';
        }

        $data = '';

        if ($len > 1) {
            MPSSE::sendAcks($this->context);
            $chunk = MPSSE::read($this->context, $len - 1);

            if (is_null($chunk)) {
                return null;
            }

            $data .= $chunk;
        }

        MPSSE::sendNacks($this->context);
        $last = MPSSE::read($this->context, 1);

        if (is_null($last)) {
            return null;
        }

        return $data.$last;
    }
}