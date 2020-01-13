<?php
/**
 * Wrapper class for the PHP LibSodium library.
 *
 * Encrypt/decrypt the given string
 * Encrypt/Decrypt the given string with base 64 encoding
 *
 * @author Joshua Mayo, Sophie Hughes, Kieran McCrory
 *
 */

namespace M2MConnect;

class LibSodiumWrapper
{
    private $key;

    public function __construct()
    {
        $this->initialiseEncryption();
    }

    public function __destruct()
    {
        sodium_memzero($this->key);
    }

    private function initialiseEncryption()
    {
        $this->key = 'Visited the Chika of good lucky!';

        if (mb_strlen($this->key, '8bit') !== SODIUM_CRYPTO_SECRETBOX_KEYBYTES) {
            throw new RangeException('Key is not the correct size (must be 32 bytes).');
        }
    }

    /**
     * Return an array containing individual values for each of the actual encrypted string and the nonce used to
     * perform the encryption
     * Need to append the two together for the decryption to work
     *
     * @param $string_to_encrypt
     * @return array
     * @throws \Exception
     */
    public function encrypt($string_to_encrypt)
    {
        $nonce = random_bytes(SODIUM_CRYPTO_SECRETBOX_NONCEBYTES);

        $encryption_data = [];

        $encrypted_string = '';
        $encrypted_string = sodium_crypto_secretbox(
            $string_to_encrypt,
            $nonce,
            $this->key
        );

        $encryption_data['nonce'] = $nonce;
        $encryption_data['encrypted_string'] = $encrypted_string;
        $encryption_data['nonce_and_encrypted_string'] = $nonce . $encrypted_string;

        sodium_memzero($string_to_encrypt);
        return $encryption_data;
    }

    public function decrypt($base64_wrapper, $string_to_decrypt)
    {
        $decrypted_string = '';
        $decoded = $base64_wrapper->decode_base64($string_to_decrypt);

        if ($decoded === false)
        {
            throw new \Exception('Ooops, the encoding failed');
        }

        if (mb_strlen($decoded, '8bit') < (SODIUM_CRYPTO_SECRETBOX_NONCEBYTES + SODIUM_CRYPTO_SECRETBOX_MACBYTES))
        {
            throw new \Exception('Oops, the message was truncated');
        }

        $nonce = mb_substr($decoded, 0, SODIUM_CRYPTO_SECRETBOX_NONCEBYTES, '8bit');

        $ciphertext = mb_substr($decoded, SODIUM_CRYPTO_SECRETBOX_NONCEBYTES, null, '8bit');

        $decrypted_string = sodium_crypto_secretbox_open(
            $ciphertext,
            $nonce,
            $this->key
        );

        if ($decrypted_string === false)
        {
            throw new \Exception('the message was tampered with in transit');
        }

        sodium_memzero($ciphertext);

        return $decrypted_string;
    }
}
