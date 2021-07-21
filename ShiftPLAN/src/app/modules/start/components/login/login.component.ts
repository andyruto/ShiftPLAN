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
import { SpinnerComponent } from 'src/app/modules/view-elements/spinner/spinner.component';

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

    //display spinner
    this.dialog.open(SpinnerComponent, {
      id: 'Login_spinnerTranslationGlobal',
      autoFocus: false,
      disableClose: true
    });

    this.translate.getTranslation(this.translate.defaultLang).subscribe((translation: any) => { 

      //close spinner
      this.dialog.getDialogById('Login_spinnerTranslationGlobal')?.close();

      this.userName = translation.Login.UserName;
      this.password = translation.Login.Password;
      this.resetBtn = translation.Login.ResetButton;
    });

    if(localStorage.getItem('Session')) {
      this.checkSession();
    }
    
  }

  ngOnDestroy() {
    //close spinner
    this.dialog.closeAll();
  }

  showPassword(): void{
    //hide or show password
  }

  private async checkSession() {

    //display spinner
    this.dialog.open(SpinnerComponent, {
      id: 'Login_spinnercheck',
      autoFocus: false,
      disableClose: true
    });

    //variables
    let publicKeyAnswer;
    let publicKeyPromise;

    let publicKeyErrorCode: number;
    let session: string = localStorage.getItem('Session') as string;
    let sessionAsync: string;
    let publicKey: string;
    var count: number;

    //get public key    
    count = Math.random() * 101;
    do {
      await this.delay(count);
    }while(this.api.isBusy);
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
    count = Math.random() * 101;
    do {
      await this.delay(count);
    }while(this.api.isBusy);
    this.api.sendPostRequest<GeneralResponse>(
      'login/', {
        apiKey: this.apiKey,
        session: sessionAsync
      }).then(response => {
        response.subscribe(answer => {
          if(answer.errorCode === 0) {
            this.router.navigate(['app/main/home']);
          }else {
            //close spinner
            this.dialog.getDialogById('Login_spinnercheck')?.close();
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
      this.loginRequest(inputName.trim(), inputPassword).then( (errorCode) => {
        if(errorCode === 0) {
          this.router.navigate(['app/main/home']);
        }
    });
    }
  }

  private async loginRequest(inputName: string, inputPassword: string) {

    //display spinner
    this.dialog.open(SpinnerComponent, {
      id: 'Login_spinnerCall',
      autoFocus: false,
      disableClose: true
    });

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
    var count: number;

    //encrypt password to hash
    let password: string = inputPassword;
    let passwordHash: string = await this.encrypt.convertToHash(password);

    //First Loginstep: send username -> get challenge
    count = Math.random() * 101;
    do {
      await this.delay(count);
    }while(this.api.isBusy);
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

          //close spinner
          this.dialog.getDialogById('Login_spinnerCall')?.close();

          this.dialog.open(LoginUserDialog, {
            autoFocus: false
          });
          return 7;
        default:

          //close spinner
          this.dialog.getDialogById('Login_spinnerCall')?.close();

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
    count = Math.random() * 101;
    do {
      await this.delay(count);
    }while(this.api.isBusy);
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

            //close spinner
            this.dialog.getDialogById('Login_spinnerCall')?.close();

            this.dialog.open(LoginPasswordDialog, {
              autoFocus: false
            });
            return 8;
          default:

            //close spinner
            this.dialog.getDialogById('Login_spinnerCall')?.close();

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

  private delay(ms: number) {
    return new Promise( resolve => setTimeout(resolve, ms) );
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

  constructor(public dialogRef: MatDialogRef<LoginEmptyDialog>, private translation: TranslateService, public dialog : MatDialog) {}

  ngOnInit(): void {

    //display spinner
    this.dialog.open(SpinnerComponent, {
      id: 'Login_spinner',
      autoFocus: false,
      disableClose: true
    });

    this.translation.getTranslation(this.translation.defaultLang).subscribe((translation: any) => {

    //close spinner
    this.dialog.getDialogById('Login_spinner')?.close();

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

  constructor(public dialogRef: MatDialogRef<LoginUserDialog>, private translation: TranslateService, public dialog : MatDialog) {}

  ngOnInit(): void {

    //display spinner
    this.dialog.open(SpinnerComponent, {
      id: 'Login_spinnerLoginUser',
      autoFocus: false,
      disableClose: true
    });

    this.translation.getTranslation(this.translation.defaultLang).subscribe((translation: any) => {

    //close spinner
    this.dialog.getDialogById('Login_spinnerLoginUser')?.close();

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

  constructor(public dialogRef: MatDialogRef<LoginPasswordDialog>, private translation: TranslateService, public dialog : MatDialog) {}

  ngOnInit(): void {

    //display spinner
    this.dialog.open(SpinnerComponent, {
      id: 'Login_spinnerLoginPassword',
      autoFocus: false,
      disableClose: true
    });

    this.translation.getTranslation(this.translation.defaultLang).subscribe((translation: any) => {

    //close spinner
    this.dialog.getDialogById('Login_spinnerLoginPassword')?.close();

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

  constructor(public dialogRef: MatDialogRef<LoginDefaultDialog>, private translation: TranslateService, public dialog : MatDialog) {}

  ngOnInit(): void {

    //display spinner
    this.dialog.open(SpinnerComponent, {
      id: 'Login_spinnerLoginDefault',
      autoFocus: false,
      disableClose: true
    });

    this.translation.getTranslation(this.translation.defaultLang).subscribe((translation: any) => {

    //close spinner
    this.dialog.getDialogById('Login_spinnerLoginDefault')?.close();

    this.warning = translation.Login.DefaultWrong;
    this.ok = translation.Login.Ok;
    });
  }
}