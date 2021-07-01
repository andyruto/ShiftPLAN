import { Injectable } from '@angular/core';
import { LoginThreeResponse } from '../models/loginthreeresponse';
import { PublicKeyResponse } from '../models/publickeyresponse';
import { ApiService } from './api.service';
import { EncryptionService } from './encryption.service';

@Injectable({
  providedIn: 'root'
})
export class UsertypeService {

  constructor(private api : ApiService,private encrypt : EncryptionService) { }

  public async getShown() {
    //variables
    let publicKeyAnswer;
    let userTypeAnswer;
    let publicKeyPromise;
    let userTypePromise;

    let publicKeyErrorCode: number;
    let userTypeErrorCode: number;
    let publicKey: string;
    let apiKey = localStorage.getItem('APIKey');
    let session = localStorage.getItem('Session') as string;
    let sessionAsync: string;
    let shown: {
      tasks: boolean,
      admin: boolean
    } = {
      tasks: false,
      admin: false
    }

    //get public key
    publicKeyAnswer = await this.api.sendPostRequest<PublicKeyResponse>(
      'key/publickey/', {
        apiKey: apiKey
      }
    );
    publicKeyPromise = await publicKeyAnswer.toPromise();
    publicKey = publicKeyPromise.publicKey;
    publicKeyErrorCode = publicKeyPromise.errorCode;

    //encrypt session asyncronous
    sessionAsync = await this.encrypt.encryptTextAsync(session, publicKey)

    //user type check
    userTypeAnswer = await this.api.sendPostRequest<LoginThreeResponse>(
      'login/', {
        apiKey: apiKey,
        session: sessionAsync
      }
    );
    userTypePromise = await userTypeAnswer.toPromise();
    userTypeErrorCode = userTypePromise.errorCode;
    switch(userTypePromise.userType) {
      case 2:
        shown.tasks = true;
        shown.admin = true;
        break;
      case 1:
        shown.tasks = true;
        break;
      default:
        break;
    }

    return shown;
  }
}