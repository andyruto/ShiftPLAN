/**
 * admin-add.component.ts
 * 
 *  Main typescript class for the admin-add component.
 * 
 * author: Anne Naumann
 * last edit / by: 2021-07-09 / Anne Naumann
 */
import { Component, OnInit, AfterViewChecked, ChangeDetectorRef } from '@angular/core';
import { TranslateService } from '@ngx-translate/core';
import { Location } from '@angular/common';
import { ApiService } from 'src/app/services/api.service';
import { EncryptionService } from 'src/app/services/encryption.service';
import { PublicKeyResponse } from 'src/app/models/publickeyresponse';
import { GeneralResponse } from 'src/app/models/generalresponse';
import { MatDialog, MatDialogRef } from '@angular/material/dialog';
import { SpinnerComponent } from 'src/app/modules/view-elements/spinner/spinner.component';

@Component({
  selector: 'app-admin-add',
  templateUrl: './admin-add.component.html',
  styleUrls: ['./admin-add.component.scss']
})
export class AdminAddComponent implements OnInit, AfterViewChecked {

  viewLoaded = false
  contentLoaded = false

  userName: string = '';
  password: string = '';
  checked: boolean = false;

  title = ''
  labelUsername = ''
  labelPassword = ''
  checkBoxUserHidden = ''
  saveBtn = ''

  constructor(
    private translate: TranslateService, 
    private location: Location,
    private api: ApiService,
    private encrypt: EncryptionService,
    public dialog : MatDialog
    ) {}

  ngOnInit(): void {

    //display spinner
    this.dialog.open(SpinnerComponent, {
      id: 'AdminAdd_spinnerTranslationGlobal',
      autoFocus: false,
      disableClose: true
    });

    this.translate.getTranslation(this.translate.defaultLang).subscribe((translation: any) => { 

      //close spinner
      this.dialog.getDialogById('AdminAdd_spinnerTranslationGlobal')?.close();

      this.title = translation.Toolbar.Title.AdminAdd;
      this.labelUsername = translation.Admin.Add.LabelUsername;
      this.labelPassword = translation.Admin.Add.LabelPassword;
      this.checkBoxUserHidden = translation.Admin.Add.CheckBoxUserHidden;
      this.saveBtn = translation.SaveButton;
    });
  }

  checking() {
    this.checked = !this.checked;
  }  
  
  ngAfterViewChecked(): void{
    let btn = document.getElementById('saveUserBtn')

    if(btn!.clientHeight != 0 && this.viewLoaded == false){
      let toolbar =  document.getElementsByTagName('mat-toolbar')[0].clientHeight
      let container = document.getElementById('buttonContainer')?.clientHeight
      var scrollView = document.getElementById('adminAddScrollView')
  
      let screenHeight = window.innerHeight-toolbar-container!
      scrollView!.style.height = screenHeight + 'px'

      this.viewLoaded = true
    }
  }

  ngOnDestroy() {
    this.dialog.closeAll();
  }

  public async addUser() {

    //check for invaled inputs
    if(this.userName == "" || this.password== "") {
      this.dialog.open(InvalidInputDialog, {
        autoFocus: false
      })
      return
    }

    //display spinner
    this.dialog.open(SpinnerComponent, {
      id: 'AdminAdd_spinnerAdd',
      autoFocus: false,
      disableClose: true
    });

    //variables
    let publicKeyAnswer;
    let addUserAnswer;
    let publicKeyPromise;
    let addUserPromise;

    let publicKeyErrorCode: number;
    let addUserErrorCode: number;
    let session: string = localStorage.getItem('Session') as string;
    let sessionAsync: string;
    let publicKey: string;
    let apiKey: string = localStorage.getItem('APIKey') as string;
    let passwordHash: string;
    let passwordAsync: string;

    //get public key
    publicKeyAnswer = await this.api.sendPostRequest<PublicKeyResponse>(
      'key/publickey/', {
        apiKey: apiKey
      }
    );
    publicKeyPromise = await publicKeyAnswer.toPromise();
    publicKey = publicKeyPromise.publicKey;
    publicKeyErrorCode = publicKeyPromise.errorCode;

    //hash new password
    passwordHash = await this.encrypt.convertToHash(this.password);

    //encrypt session and password hash asyncronous
    sessionAsync = await this.encrypt.encryptTextAsync(session, publicKey);
    passwordAsync = await this.encrypt.encryptTextAsync(passwordHash,publicKey);

    //send add user request
    addUserAnswer = await this.api.sendPostRequest<GeneralResponse>(
      'users/create/', {
        apiKey: apiKey,
        session: sessionAsync,
        name: this.userName,
        pwHash: passwordAsync,
        hidden: !this.checked
      }
    );
    addUserPromise = await addUserAnswer.toPromise();
    console.log(addUserPromise)
    addUserErrorCode = addUserPromise.errorCode;

    this.location.back();
  }
}

@Component({
  selector: 'invalid-data-dialog',
  templateUrl: 'dialog.html',
  styleUrls: ['./admin-add.component.scss'],
})
export class InvalidInputDialog {
  warning = '';
  ok = '';

  constructor(public dialogRef: MatDialogRef<InvalidInputDialog>, private translation: TranslateService, public dialog : MatDialog) {}

  ngOnInit(): void {

    //display spinner
    this.dialog.open(SpinnerComponent, {
      id: 'AdminAdd_spinner',
      autoFocus: false,
      disableClose: true
    });

    this.translation.getTranslation(this.translation.defaultLang).subscribe((translation: any) => {

    //close spinner
    this.dialog.getDialogById('AdminAdd_spinner')?.close();

    this.warning = translation.Admin.Add.InputWarning;
    this.ok = translation.Admin.Add.Ok;
    });
  }

  closeDialog(): void {
    this.dialogRef.close();
  }
}
