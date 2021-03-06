/**
 * admin-add.component.ts
 * 
 *  Main typescript class for the admin-add component.
 * 
 * author: Anne Naumann
 * last edit / by: 2021-07-20 / Anne Naumann
 */
import { Component, OnInit, AfterViewChecked, ChangeDetectorRef, HostListener } from '@angular/core';
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

  labelUsername = '';
  labelPassword = '';

  checked: boolean = false;
  labelRole = '';
  roleAdmin = '';
  roleBoss = '';
  roleEmployee = '';
  labelOvertime = '';
  labelWorkingMinutes = '';
  labelWorkingDays = '';
  labelVacationDays = '';

  title = '';
  role = '0';
  userName: string = '';
  password: string = '';
  checkBoxUserHidden = '';
  overtime: number = 0;
  minutes: number = 0;
  days: number = 0;
  vacation: number = 0;
  saveBtn = '';
  position: string = 'absolute';
  originalHeight: number = window.innerHeight;


  constructor(
    private translate: TranslateService, 
    private location: Location,
    private api: ApiService,
    private encrypt: EncryptionService,
    public dialog : MatDialog,
    private cdRef:ChangeDetectorRef
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
      this.labelRole = translation.Admin.Add.LabelRole;
      this.roleAdmin = translation.Admin.Add.RoleAdmin;
      this.roleBoss = translation.Admin.Add.RoleBoss;
      this.roleEmployee = translation.Admin.Add.RoleEmployee;
      this.labelOvertime = translation.Admin.Add.LabelOvertime;
      this.labelWorkingMinutes = translation.Admin.Add.LabelWorkingMinutes;
      this.labelWorkingDays = translation.Admin.Add.LabelWorkingDays;
      this.labelVacationDays = translation.Admin.Add.LabelVacationDays;

      this.cdRef.detectChanges();
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

  @HostListener('window:resize', ['$event'])
  setBtnPosition() {
    let currentHeight: number = window.innerHeight;
    if(currentHeight < this.originalHeight) {
      this.position = 'static';
    }else {
      this.position = 'absolute';
    }
  }

  public async addUser() {

    //check for invaled inputs
    if(this.userName == "" || this.password== "") {
      this.dialog.open(InvalidDataDialog, {
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
    var count: number;

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
    publicKey = publicKeyPromise.publicKey;
    publicKeyErrorCode = publicKeyPromise.errorCode;

    //hash new password
    passwordHash = await this.encrypt.convertToHash(this.password);

    //encrypt session and password hash asyncronous
    sessionAsync = await this.encrypt.encryptTextAsync(session, publicKey);
    passwordAsync = await this.encrypt.encryptTextAsync(passwordHash,publicKey);

    //send add user request
    count = Math.random() * 101;
    do {
      await this.delay(count);
    }while(this.api.isBusy);
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
    addUserErrorCode = addUserPromise.errorCode;

    this.modifyUser();

    this.location.back();
  }

  private async modifyUser() {
    //variables
    let publicKeyAnswer;
    let modifyAnswer;
    let publicKeyPromise;
    let modifyPromise;

    let publicKeyErrorCode: number;
    let modifyErrorCode: number;
    let publicKey: string;
    let sessionAsync: string;
    let passwordHash: string;
    let passwordAsync: string;
    let apiKey: string = localStorage.getItem('APIKey') as string;
    let session: string = localStorage.getItem('Session') as string;
    var count:number;

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
    publicKey = publicKeyPromise.publicKey;
    publicKeyErrorCode = publicKeyPromise.errorCode;

    //encrypt session asyncronous
    sessionAsync = await this.encrypt.encryptTextAsync(session, publicKey);

    //hash & encrypt new password
    passwordHash = await this.encrypt.convertToHash(this.password);
    passwordAsync = await this.encrypt.encryptTextAsync(passwordHash,publicKey);

    //modifyUser
    count = Math.random() * 101;
    do {
      await this.delay(count);
    }while(this.api.isBusy);
    modifyAnswer = await this.api.sendPostRequest<GeneralResponse>(
      'users/modify/', {
        apiKey: apiKey,
        session: sessionAsync,
        user: this.userName,
        type: this.role,
        overtime: this.overtime,
        weeklyWorkingMinutes: this.minutes,
        weeklyWorkingDays: this.days,
        yearVacationDays: this.vacation
      }
    );
    modifyPromise = await modifyAnswer.toPromise();
    modifyErrorCode = modifyPromise.errorCode;
  }

  private delay(ms: number) {
    return new Promise( resolve => setTimeout(resolve, ms) );
  }
}

@Component({
  selector: 'invalid-data-dialog',
  templateUrl: 'dialog.html',
  styleUrls: ['./admin-add.component.scss'],
})
export class InvalidDataDialog {
  warning = '';
  ok = '';

  constructor(public dialogRef: MatDialogRef<InvalidDataDialog>, private translation: TranslateService, public dialog : MatDialog) {}

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
}
