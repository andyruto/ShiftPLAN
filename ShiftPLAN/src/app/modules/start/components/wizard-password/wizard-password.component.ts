/**
 * wizard-password.component.ts
 * 
 * Main typescript class for the wizard-password component.
 * 
 * author: Anne Naumann
 * last edit / by: 2021-06-30 / Anne Naumann
 */
import { Component, OnInit } from '@angular/core';
import { TranslateService } from '@ngx-translate/core';
import { MatDialog, MatDialogRef } from '@angular/material/dialog';
import { ApiService } from 'src/app/services/api.service';
import { LoginOneResponse } from 'src/app/models/loginoneresponse';
import { LoginTwoResponse } from 'src/app/models/logintworesponse';
import { EncryptionService } from 'src/app/services/encryption.service';
import { GeneralResponse } from 'src/app/models/generalresponse';
import { PublicKeyResponse } from 'src/app/models/publickeyresponse';
import { Router } from '@angular/router';
import { SpinnerComponent } from 'src/app/modules/view-elements/spinner/spinner.component';

@Component({
  selector: 'app-wizard-password',
  templateUrl: './wizard-password.component.html',
  styleUrls: ['./wizard-password.component.scss']
})
export class WizardPasswordComponent implements OnInit{

  title = ''
  labelPasswordNew = ''
  labelPasswordCheck = ''
  warningNew = ''
  nextButton = ''
  session = ''
  apiKey = localStorage.getItem('APIKey')

  constructor(
    private translate : TranslateService, 
    public dialog : MatDialog,
    private api : ApiService,
    private encrypt : EncryptionService,
    private router : Router) { }
  
  ngOnInit(): void {

    //display spinner
    this.dialog.open(SpinnerComponent, {
      id: 'WizardPassword_spinnerTranslationGlobal',
      autoFocus: false,
      disableClose: true
    });

    this.translate.getTranslation(this.translate.defaultLang).subscribe((translation: any) => { 

      //close spinner
      this.dialog.getDialogById('WizardPassword_spinnerTranslationGlobal')?.close();

      this.title = translation.WizardPassword.Title;
      this.labelPasswordNew = translation.WizardPassword.LabelPasswordNew;
      this.labelPasswordCheck = translation.WizardPassword.LabelPasswordCheck;
      this.warningNew = translation.WizardPassword.WarningNew;
      this.nextButton = translation.NextButton;
    });
  }

  setPassword(password: string, passwordCheck: string){
    if(password === '') {
      this.dialog.open(InvalidPasswordDialog, {
        autoFocus: false
      });
    }else {
      if(password === passwordCheck) {

        //display spinner
          this.dialog.open(SpinnerComponent, {
          id: 'WizardPassword_spinnerCall',
          autoFocus: false,
          disableClose: true
        });

        this.loginRequest().then( _ => {
          this.changePassword(password).then( _ => {

            //close spinner
            this.dialog.closeAll();

            this.router.navigate(['start/wizardPermission']);
          });
        });

    }else {
      this.dialog.open(PasswordDialog, {
        autoFocus: false
      });
    }
    }
    
  }

  private async changePassword(newPassword: string) {

    //variables
    let publicKeyAnswer;
    let modifyAnswer;
    let publicKeyPromise;
    let modifyPromise;

    let publicKeyErrorCode: number;
    let modifyErrorCode: number;
    let publicKey: string;
    let passwordHash: string;
    let passwordAsync: string;
    let sessionAsync: string;
    let oldPasswordHash: string;
    let nonce: string;
    
    //get public key
    publicKeyAnswer = await this.api.sendPostRequest<PublicKeyResponse>(
      'key/publickey/', {
        apiKey: this.apiKey
      }
    );
    publicKeyPromise = await publicKeyAnswer.toPromise();
    publicKey = publicKeyPromise.publicKey;
    publicKeyErrorCode = publicKeyPromise.errorCode;

    //hash new password
    passwordHash = await this.encrypt.convertToHash(newPassword);

    //encrypt session asyncronous
    sessionAsync = await this.encrypt.encryptTextAsync(this.session, publicKey);

    //encrypt new password asyncronous
    passwordAsync = await this.encrypt.encryptTextAsync(passwordHash, publicKey)

    //change password
    modifyAnswer = await this.api.sendPostRequest<GeneralResponse>(
      'users/modify/', {
        apiKey: this.apiKey,
        session: sessionAsync,
        user: 'admin',
        password: passwordAsync
      }
    );
    modifyPromise = await modifyAnswer.toPromise();
    modifyErrorCode = modifyPromise.errorCode;
  }

  private async loginRequest() {

    //variables
    let firstAnswer;
    let secondAnswer;
    let firstPromise;
    let secondPromise;

    let firstErrorCode: number ;
    let secondErrorCode: number;
    let chlg: string;
    let nonce: string;
    let chlgSolved: string;

    //encrypt password to hash
    let standardPassword: string = 'admin';
    let standardPasswordHash: string = await this.encrypt.convertToHash(standardPassword);
    
    //First Loginstep: send username -> get challenge
    firstAnswer = await this.api.sendPostRequest<LoginOneResponse>(
      'login/', {
        apiKey: this.apiKey,
        userName: 'admin'
    });
    firstPromise = await firstAnswer.toPromise();
    firstErrorCode = firstPromise.errorCode;
    chlg = firstPromise.chlg;
    
    //Solve challenge
    nonce = await this.encrypt.generateNonce();
    chlgSolved = await this.encrypt.encryptTextSync(
      chlg,
      nonce,
      standardPasswordHash
    );

    //Second Loginstep: send solved challenge -> get session
    secondAnswer = await this.api.sendPostRequest<LoginTwoResponse>(
      'login/', {
        apiKey: this.apiKey,
        userName: 'admin',
        chlgSolved: chlgSolved,
        nonce: nonce
    });
    secondPromise = await secondAnswer.toPromise();
    secondErrorCode = secondPromise.errorCode;

    //decrypt session
    this.session = await this.encrypt.decryptTextSync(
      secondPromise.session,
      secondPromise.nonce,
      standardPasswordHash
    );

    //write session into local storage
    localStorage.setItem('Session', this.session);
  }
}

@Component({
  selector: 'password-dialog',
  templateUrl: 'dialog.html',
  styleUrls: ['./wizard-password.component.scss'],
})
export class PasswordDialog {
  warning = '';
  ok = '';

  constructor(public dialogRef: MatDialogRef<PasswordDialog>, private translation: TranslateService, public dialog : MatDialog) {}

  ngOnInit(): void {

    //display spinner
    this.dialog.open(SpinnerComponent, {
      id: 'PasswordDialog_spinner',
      autoFocus: false,
      disableClose: true
    });

    this.translation.getTranslation(this.translation.defaultLang).subscribe((translation: any) => {

    //close spinner
    this.dialog.getDialogById('PasswordDialog_spinner')?.close();

    this.warning = translation.WizardPassword.WarningNew;
    this.ok = translation.WizardPassword.Ok;
    });
  }
}

@Component({
  selector: 'invalidpassword-dialog',
  templateUrl: 'dialog.html',
  styleUrls: ['./wizard-password.component.scss'],
})
export class InvalidPasswordDialog {
  warning = '';
  ok = '';

  constructor(public dialogRef: MatDialogRef<InvalidPasswordDialog>, private translation: TranslateService, public dialog : MatDialog) {}

  ngOnInit(): void {

    //display spinner
    this.dialog.open(SpinnerComponent, {
      id: 'InvalidPasswordDialog_spinner',
      autoFocus: false,
      disableClose: true
    });

    this.translation.getTranslation(this.translation.defaultLang).subscribe((translation: any) => {

    //close spinner
    this.dialog.getDialogById('InvalidPasswordDialog_spinner')?.close();

    this.warning = translation.WizardPassword.InvalidPassword;
    this.ok = translation.WizardPassword.Ok;
    });
  }
}