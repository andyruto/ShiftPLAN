/**
 * login.component.ts
 * 
 * Main typescript class for the login component.
 * 
 * author: Anne Naumann
 * last edit / by: 2021-06-30 / Anne Naumann
 */
import { Component, OnInit } from '@angular/core';
import { Router } from '@angular/router';
import { TranslateService } from '@ngx-translate/core';
import { GeneralResponse } from 'src/app/models/generalresponse';
import { LoginOneResponse } from 'src/app/models/loginoneresponse';
import { LoginTwoResponse } from 'src/app/models/logintworesponse';
import { ApiService } from 'src/app/services/api.service';
import { EncryptionService } from 'src/app/services/encryption.service';
import { MatDialog, MatDialogRef } from '@angular/material/dialog';
import { PublicKeyResponse } from 'src/app/models/publickeyresponse';

@Component({
  selector: 'app-login',
  templateUrl: './login.component.html',
  styleUrls: ['./login.component.scss']
})
export class LoginComponent implements OnInit {

  userName = ''
  password = ''
  resetBtn = ''
  apiKey = localStorage.getItem('APIKey');

  constructor(
    private translate : TranslateService,
    private api : ApiService,
    private router : Router,
    private encrypt : EncryptionService,
    public dialog : MatDialog) { }

  ngOnInit(): void {
    this.translate.getTranslation(this.translate.defaultLang).subscribe((translation: any) => { 
      this.userName = translation.Login.UserName;
      this.password = translation.Login.Password;
      this.resetBtn = translation.Login.ResetButton;
    });
    this.checkSession();
  }

  showPassword(): void{
    //hide or show password
  }

  private async checkSession() {

    //variables
    let publicKeyAnswer;
    let publicKeyPromise;

    let publicKeyErrorCode: number;
    let session: string = localStorage.getItem('Session') as string;
    let sessionAsync: string;
    let publicKey: string;

    //get public key
    publicKeyAnswer = await this.api.sendPostRequest<PublicKeyResponse>(
      'key/publickey/', {
        apiKey: this.apiKey
      }
    );
    publicKeyPromise = await publicKeyAnswer.toPromise();
    publicKey = publicKeyPromise.publicKey;
    publicKeyErrorCode = publicKeyPromise.errorCode;

    //encrypt session asyncronous
    sessionAsync = await this.encrypt.encryptTextAsync(session, publicKey)

    //check if session is still open
    this.api.sendPostRequest<GeneralResponse>(
      'login/', {
        apiKey: this.apiKey,
        session: sessionAsync
      }).then(response => {
        response.subscribe(answer => {
          if(answer.errorCode === 0) {
            this.router.navigate(['app/main/home']);
          }
        });
      });
  }

  checkLoginValues(inputName: string, inputPassword: string){
    if(inputName === '' || inputPassword === ''){
      this.dialog.open(LoginEmptyDialog, {
        autoFocus: false
      });
    }else {
      this.loginRequest(inputName, inputPassword).then( (errorCode) => {
        if(errorCode === 0) {
          this.router.navigate(['app/main/home']);
        }
    });
    }
    
  }

  private async loginRequest(inputName: string, inputPassword: string) {
    //variables
    let firstAnswer;
    let secondAnswer;
    let firstPromise;
    let secondPromise;

    let firstErrorCode: number;
    let secondErrorCode: number;
    let chlg: string;
    let nonce: string;
    let chlgSolved: string;
    let session: string;

    //encrypt password to hash
    let password: string = inputPassword;
    let passwordHash: string = await this.encrypt.convertToHash(password);

    //First Loginstep: send username -> get challenge
    firstAnswer = await this.api.sendPostRequest<LoginOneResponse>(
      'login/', {
        apiKey: this.apiKey,
        userName: inputName
    });
    firstPromise = await firstAnswer.toPromise();
    firstErrorCode = firstPromise.errorCode;
    if(firstErrorCode != 0) {
      switch(firstErrorCode) {
        case 7:
          this.dialog.open(LoginUserDialog, {
            autoFocus: false
          });
          return 7;
        default:
          this.dialog.open(LoginDefaultDialog, {
            autoFocus: false
          });
          return -1;     
      }
    }
    chlg = firstPromise.chlg;

    //Solve challenge
    nonce = await this.encrypt.generateNonce();
    chlgSolved = await this.encrypt.encryptTextSync(
      chlg,
      nonce,
      passwordHash
    );

    //Second Loginstep: send solved challenge -> get session
    secondAnswer = await this.api.sendPostRequest<LoginTwoResponse>(
      'login/', {
        apiKey: this.apiKey,
        userName: inputName,
        chlgSolved: chlgSolved,
        nonce: nonce
    });
    secondPromise = await secondAnswer.toPromise();
    
    secondErrorCode = secondPromise.errorCode;
      if(secondErrorCode != 0) {
        switch(secondErrorCode) {
          case 10:
            this.dialog.open(LoginPasswordDialog, {
              autoFocus: false
            });
            return 8;
          default:
            this.dialog.open(LoginDefaultDialog, {
              autoFocus: false
            });
            return -1;        
        }
      }

    //decrypt session
    session = await this.encrypt.decryptTextSync(
      secondPromise.session,
      secondPromise.nonce,
      passwordHash
    );
    
    //write session into local storage
    localStorage.setItem('Session', session);

    return 0;
  }

  resetPassword(): void{
    //TODO: implement function to reset password
  }
}

@Component({
  selector: 'login-empty-dialog',
  templateUrl: 'dialog.html',
  styleUrls: ['./login.component.scss'],
})
export class LoginEmptyDialog {
  warning = '';
  ok = '';

  constructor(public dialogRef: MatDialogRef<LoginEmptyDialog>, private translation: TranslateService) {}

  ngOnInit(): void {
    this.translation.getTranslation(this.translation.defaultLang).subscribe((translation: any) => {
    this.warning = translation.Login.Empty;
    this.ok = translation.Login.Ok;
    });
  }
}

@Component({
  selector: 'login-user-dialog',
  templateUrl: 'dialog.html',
  styleUrls: ['./login.component.scss'],
})
export class LoginUserDialog {
  warning = '';
  ok = '';

  constructor(public dialogRef: MatDialogRef<LoginUserDialog>, private translation: TranslateService) {}

  ngOnInit(): void {
    this.translation.getTranslation(this.translation.defaultLang).subscribe((translation: any) => {
    this.warning = translation.Login.UserWrong;
    this.ok = translation.Login.Ok;
    });
  }
}

@Component({
  selector: 'login-password-dialog',
  templateUrl: 'dialog.html',
  styleUrls: ['./login.component.scss'],
})
export class LoginPasswordDialog {
  warning = '';
  ok = '';

  constructor(public dialogRef: MatDialogRef<LoginPasswordDialog>, private translation: TranslateService) {}

  ngOnInit(): void {
    this.translation.getTranslation(this.translation.defaultLang).subscribe((translation: any) => {
    this.warning = translation.Login.PasswordWrong;
    this.ok = translation.Login.Ok;
    });
  }
}

@Component({
  selector: 'login-default-dialog',
  templateUrl: 'dialog.html',
  styleUrls: ['./login.component.scss'],
})
export class LoginDefaultDialog {
  warning = '';
  ok = '';

  constructor(public dialogRef: MatDialogRef<LoginDefaultDialog>, private translation: TranslateService) {}

  ngOnInit(): void {
    this.translation.getTranslation(this.translation.defaultLang).subscribe((translation: any) => {
    this.warning = translation.Login.DefaultWrong;
    this.ok = translation.Login.Ok;
    });
  }
}