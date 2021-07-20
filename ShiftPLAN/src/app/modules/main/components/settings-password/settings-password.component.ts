/**
 * settings-password.component.ts
 * 
 *  Main typescript class for the settings-password component.
 * 
 * author: Anne Naumann
 * last edit / by: 2021-07-20 / Anne Naumann
 */
import { Component, OnInit } from '@angular/core';
import { TranslateService } from '@ngx-translate/core';
import { Location } from '@angular/common'
import { SpinnerComponent } from 'src/app/modules/view-elements/spinner/spinner.component';
import { MatDialog, MatDialogRef } from '@angular/material/dialog';
import { ApiService } from 'src/app/services/api.service';
import { EncryptionService } from 'src/app/services/encryption.service';
import { PublicKeyResponse } from 'src/app/models/publickeyresponse';
import { GeneralResponse } from 'src/app/models/generalresponse';

@Component({
  selector: 'app-settings-password',
  templateUrl: './settings-password.component.html',
  styleUrls: ['./settings-password.component.scss']
})
export class SettingsPasswordComponent implements OnInit {

  title = '';
  nextBtn = '';
  saveBtn = '';
  labelPassword = '';
  labelNewPassword = '';
  labelCheckPassword = '';
  warningCheck = '';
  warningSet = '';
  oldPassword: string = '';
  newPassword: string = '';
  checkPassword: string = '';

  checkDiv: any;
  setDiv: any;

  constructor(
    private translate: TranslateService, 
    private location: Location, 
    public dialog: MatDialog,
    private api: ApiService,
    private encrypt: EncryptionService
    ) { }

  ngOnInit(): void {

    //display spinner
    this.dialog.open(SpinnerComponent, {
      id: 'SettingsPassword_spinnerTranslationGlobal',
      autoFocus: false,
      disableClose: true
    });

    this.translate.getTranslation(this.translate.defaultLang).subscribe((translation: any) => { 

      //close spinner
      this.dialog.getDialogById('SettingsPassword_spinnerTranslationGlobal')?.close();

      this.title = translation.Settings.LabelPassword;
      this.nextBtn = translation.NextButton;
      this.saveBtn = translation.SaveButton;
      this.labelPassword = translation.Settings.Password.LabelPassword;
      this.labelNewPassword = translation.Settings.Password.LabelNewPassword;
      this.labelCheckPassword = translation.Settings.Password.LabelCheckPassword;
      this.warningCheck = translation.Settings.Password.WarningCheck;
      this.warningSet = translation.Settings.Password.WarningSet;
    });

    this.checkDiv = document.getElementById('checkPassword');
    this.setDiv = document.getElementById('setPassword');
  }

  ngOnDestroy() {
    this.dialog.closeAll();
  }

  showSetPassword(): void{

    if(this.oldPassword == '') {
      this.dialog.open(DifferentInputDialog, {
        autoFocus: false
      });
      return
    }
    this.checkDiv!.style.display = 'none'
    this.setDiv!.style.display = 'block'
  }

  async setNewPassword(){

    if(this.newPassword != this.checkPassword) {
      this.dialog.open(DifferentInputDialog, {
        autoFocus: false
      });
      return
    }

    //display spinner
    this.dialog.open(SpinnerComponent, {
      id: 'SettingsPassword_spinnerSetNewPassword',
      autoFocus: false,
      disableClose: true
    });
    
    //variables
    let publicKeyAnswer;
    let setNewPasswordAnswer;
    let publicKeyPromise;
    let setNewPasswordPromise;

    let publicKeyErrorCode: number;
    let setNewPasswordErrorCode: number;
    let session: string = localStorage.getItem('Session') as string;
    let sessionAsync: string;
    let publicKey: string;
    let apiKey: string = localStorage.getItem('APIKey') as string;
    let oldPasswordHash: string;
    let newPasswordHash: string;
    let newPasswordSync: string;
    let nonce: string;

    //get public key
    publicKeyAnswer = await this.api.sendPostRequest<PublicKeyResponse>(
      'key/publickey/', {
        apiKey: apiKey
      }
    );
    publicKeyPromise = await publicKeyAnswer.toPromise();
    publicKey = publicKeyPromise.publicKey;
    publicKeyErrorCode = publicKeyPromise.errorCode;

    //hash new and old password
    oldPasswordHash = await this.encrypt.convertToHash(this.oldPassword);
    newPasswordHash = await this.encrypt.convertToHash(this.newPassword);

    //encrypt session asyncronous
    sessionAsync = await this.encrypt.encryptTextAsync(session, publicKey);

    //encrypt new password syncronous
    nonce = await this.encrypt.generateNonce();
    newPasswordSync = await this.encrypt.encryptTextSync(newPasswordHash, nonce, oldPasswordHash)

    //set new Password
    setNewPasswordAnswer = await this.api.sendPostRequest<GeneralResponse>(
      'users/modify/', {
        apiKey: apiKey,
        session: sessionAsync,
        password: newPasswordSync,
        nonce: nonce
      }
    )
    setNewPasswordPromise = await setNewPasswordAnswer.toPromise();
    setNewPasswordErrorCode = setNewPasswordPromise.errorCode;

    if(setNewPasswordErrorCode != 0) {
      this.dialog.open(WrongInputDialog, {
        autoFocus: false
      });
      this.checkDiv!.style.display = 'block'
      this.setDiv!.style.display = 'none'

      //close spinner
      this.dialog.getDialogById('SettingsPassword_spinnerSetNewPassword')?.close();
      return
    }

    this.location.back();
  }
}

@Component({
  selector: 'different-input-dialog',
  templateUrl: 'dialog.html',
  styleUrls: ['./settings-password.component.scss'],
})
export class DifferentInputDialog {
  warning = '';
  ok = '';

  constructor(public dialogRef: MatDialogRef<DifferentInputDialog>, private translation: TranslateService, public dialog : MatDialog) {}

  ngOnInit(): void {

    //display spinner
    this.dialog.open(SpinnerComponent, {
      id: 'SettingsPassword_spinner',
      autoFocus: false,
      disableClose: true
    });

    this.translation.getTranslation(this.translation.defaultLang).subscribe((translation: any) => {

    //close spinner
    this.dialog.getDialogById('SettingsPassword_spinner')?.close();

    this.warning = translation.Settings.Password.WarningCheckPassword;
    this.ok = translation.Settings.Password.Ok;
    });
  }
}

@Component({
  selector: 'wrong-input-dialog',
  templateUrl: 'dialog.html',
  styleUrls: ['./settings-password.component.scss'],
})
export class WrongInputDialog {
  warning = '';
  ok = '';

  constructor(public dialogRef: MatDialogRef<WrongInputDialog>, private translation: TranslateService, public dialog : MatDialog) {}

  ngOnInit(): void {

    //display spinner
    this.dialog.open(SpinnerComponent, {
      id: 'SettingsPassword_spinnerWrongInput',
      autoFocus: false,
      disableClose: true
    });

    this.translation.getTranslation(this.translation.defaultLang).subscribe((translation: any) => {

    //close spinner
    this.dialog.getDialogById('SettingsPassword_spinnerWrongInput')?.close();

    this.warning = translation.Settings.Password.WarningPassword;
    this.ok = translation.Settings.Password.Ok;
    });
  }
}



