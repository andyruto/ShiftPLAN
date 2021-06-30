/**
 * encryption.service.ts
 * 
 * This service provides easy to use methods for encryption
 * to the whole frontend structure.
 * 
 * author: Sascha W.
 * last edit / by: 2021-06-05 / Anne Naumann
 */

// imports:
import { Injectable } from '@angular/core';
import { generateSodium } from 'src/app/services/encryption.service.helper';

// marks this service as singelton
@Injectable({
  providedIn: 'root'
})
export class EncryptionService {

  // variables:
  sodium: any = undefined;

  constructor() { }

  // checks if a instance of sodium is saved on the variable "sodium"
  private async checkSodium() {
    if(this.sodium == undefined) {
      this.sodium = await generateSodium();
    }
  }

  // synchronous encryption:

  // generate a random nonce for synchronous encryption
  public async generateNonce() {
    await this.checkSodium();
    return this.sodium.to_hex(
      this.sodium.randombytes_buf(
        this.sodium.crypto_secretbox_NONCEBYTES
      )
    );
  }

  // encrypt a text synchronous
  public async encryptTextSync(text: string, nonce: string, passwordHash: string) {
    await this.checkSodium();
    return this.sodium.to_hex(
        this.sodium.crypto_secretbox_easy(
            text, 
            this.sodium.from_hex(nonce), 
            this.sodium.from_hex(passwordHash)
        )
    );
  }

  // decrypt a syncronous enrypted text
  public async decryptTextSync(crypt: string , nonce: string, passwordHash: string) {
    await this.checkSodium();
    return this.sodium.to_string(
        this.sodium.crypto_secretbox_open_easy(
            this.sodium.from_hex(crypt), 
            this.sodium.from_hex(nonce), 
            this.sodium.from_hex(passwordHash)
          )
      );
  }

  // asynchronous encryption

  // encrypt a text desynchronous
  public async encryptTextAsync(message: string, publicKey: string) {
    await this.checkSodium();
    return this.sodium.to_hex(
        this.sodium.crypto_box_seal(
            message, 
            this.sodium.from_hex(publicKey)
        )
    );
  }

  // text to hash function for password encryption

  // convert text to a hash value
  public async convertToHash(text: string) {
    await this.checkSodium();
    return this.sodium.to_hex(
      this.sodium.crypto_generichash(
        this.sodium.crypto_hash_sha256_BYTES, 
        text
      )
    );
  }
}
