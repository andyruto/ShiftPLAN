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
    var count: number;

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
    count = Math.random() * 101;
    do {
      await this.delay(count);
    }while(this.api.isBusy);
    publicKeyAnswer = await this.api.sendPostRequest<PublicKeyResponse>(
      'key/publickey/', {
        apiKey: apiKey
      }
    );
    publicKeyPromise = await publicKeyAnswer.toPromise();
    //DEBUG
    console.log(publicKeyPromise)
    publicKey = publicKeyPromise.publicKey;
    publicKeyErrorCode = publicKeyPromise.errorCode;

    //encrypt session asyncronous
    sessionAsync = await this.encrypt.encryptTextAsync(session, publicKey);

    //user type check
    count = Math.random() * 101;
    do {
      await this.delay(count);
    }while(this.api.isBusy);
    userTypeAnswer = await this.api.sendPostRequest<LoginThreeResponse>(
      'login/', {
        apiKey: apiKey,
        session: sessionAsync
      }
    );
    userTypePromise = await userTypeAnswer.toPromise();
    //DEBUG
    console.log(userTypePromise);
    userTypeErrorCode = userTypePromise.errorCode;
    switch(userTypePromise.userType) {
      case 2:
        shown.tasks = true;
        shown.admin = true;
        break;
      case 1:
        shown.tasks = true;
        shown.admin = true;
        break;
      default:
        break;
    }

    return shown;
  }

  private delay(ms: number) {
    return new Promise( resolve => setTimeout(resolve, ms) );
  }
}