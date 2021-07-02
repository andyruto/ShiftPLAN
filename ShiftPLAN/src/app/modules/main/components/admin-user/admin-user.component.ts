/**
 * admin-user.component.ts
 * 
 *  Main typescript class for the admin-user component.
 * 
 * author: Anne Naumann
 * last edit / by: 2021-06-11 / Anne Naumann
 */
import { Component, OnInit } from '@angular/core';
import { TranslateService } from '@ngx-translate/core';
import { Location } from '@angular/common';
import { MatDialog, MatDialogRef } from '@angular/material/dialog';
import { PublicKeyResponse } from 'src/app/models/publickeyresponse';
import { ApiService } from 'src/app/services/api.service';
import { EncryptionService } from 'src/app/services/encryption.service';
import { SpinnerComponent } from 'src/app/modules/view-elements/spinner/spinner.component';

@Component({
  selector: 'app-admin-user',
  templateUrl: './admin-user.component.html',
  styleUrls: ['./admin-user.component.scss']
})
export class AdminUserComponent implements OnInit {

  title = ''
  labelUsername = ''
  labelPassword = ''
  checkBoxUserHidden = ''
  saveBtn = ''
  hidden: boolean = false;
  checked: boolean = false;
  public userName: string = ''

  constructor(
    private translate: TranslateService, 
    private location: Location,
    private dialog: MatDialog,
    private api: ApiService,
    private encrypt: EncryptionService
    ) { }

  ngOnInit(): void {

    //display spinner
    this.dialog.open(SpinnerComponent, {
      id: 'AdminUser_spinnerTranslationGlobal',
      autoFocus: false,
      disableClose: true
    });

    this.translate.getTranslation(this.translate.defaultLang).subscribe((translation: any) => { 

      //close spinner
      this.dialog.getDialogById('AdminUser_spinnerTranslationGlobal')?.close();

      this.title = translation.Toolbar.Title.AdminUser;
      this.labelUsername = translation.Admin.Add.LabelUsername;
      this.labelPassword = translation.Admin.Add.LabelPassword;
      this.checkBoxUserHidden = translation.Admin.Add.CheckBoxUserHidden;
      this.saveBtn = translation.SaveButton;
    });

    let sdfg: string = window.history.state.user;
    console.log(sdfg)
    this.userName = sdfg
  }

  changeUser(userName: string) {
    let userVisible = document.getElementById('checkBoxUserHidden')?.classList.contains('mat-checkbox-checked')
    console.log(userVisible);
    this.modifyUser(userName, userVisible as boolean);
  }

  private async fill() {
    let sdfg: string = window.history.state;
    console.log(sdfg)
    this.userName = sdfg
  }

  private async modifyUser(userName: string, userVisible: boolean) {

    //variables
    let publicKeyAnswer;
    let modifyAnswer;
    let publicKeyPromise;
    let modifyPromise;

    let publicKeyErrorCode: number;
    let modifyErrorCode: number;
    let publicKey: string;
    let sessionAsync: string;
    let apiKey: string = localStorage.getItem('APIKey') as string;
    let session: string = localStorage.get('Session') as string;

    //get public key
    publicKeyAnswer = await this.api.sendPostRequest<PublicKeyResponse>(
      'key/publickey/', {
        apiKey: apiKey
      }
    );
    publicKeyPromise = await publicKeyAnswer.toPromise();
    publicKey = publicKeyPromise.publicKey;
    publicKeyErrorCode = publicKeyPromise.errorCode;

    //encrypt new password asyncronous
    sessionAsync = await this.encrypt.encryptTextAsync(session, publicKey);
  }
  
}

@Component({
  selector: 'invalid-data-dialog',
  templateUrl: 'dialog.html',
  styleUrls: ['./admin-user.component.scss'],
})
export class InvalidPasswordDialog {
  warning = '';
  ok = '';

  constructor(public dialogRef: MatDialogRef<InvalidPasswordDialog>, private translation: TranslateService, public dialog : MatDialog) {}

  ngOnInit(): void {

    //display spinner
    this.dialog.open(SpinnerComponent, {
      id: 'AdminUser_spinner',
      autoFocus: false,
      disableClose: true
    });

    this.translation.getTranslation(this.translation.defaultLang).subscribe((translation: any) => {

    //close spinner
    this.dialog.getDialogById('AdminUser_spinner')?.close();

    this.warning = translation.WizardPassword.InvalidPassword;
    this.ok = translation.WizardPassword.Ok;
    });
  }

  closeDialog(): void {
    this.dialogRef.close();
  }
}
