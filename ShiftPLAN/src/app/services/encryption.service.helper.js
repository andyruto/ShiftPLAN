/**
 * encryption.service.helper.js
 * 
 * JavaScript file to help the encryption service 
 * with the Sodium Framework.
 * 
 * author: Sascha W.
 * last edit / by: 2021-05-22 / Sascha W.
 */

// imports:
const _sodium = require('libsodium-wrappers-sumo');

// generate sodium
export async function generateSodium() {
    await _sodium.ready;
    return _sodium;
}