/**
 * admin-user.component.ts
 * 
 *  Main typescript class for the admin-user component.
 * 
 * author: Anne Naumann
 * last edit / by: 2021-07-09 / Anne Naumann
 */
import { Component, OnInit, AfterViewChecked } from '@angular/core';
import { TranslateService } from '@ngx-translate/core';
import { Location } from '@angular/common';
import { MatDialog, MatDialogRef } from '@angular/material/dialog';
import { PublicKeyResponse } from 'src/app/models/publickeyresponse';
import { ApiService } from 'src/app/services/api.service';
import { EncryptionService } from 'src/app/services/encryption.service';
import { SpinnerComponent } from 'src/app/modules/view-elements/spinner/spinner.component';
import { GetUserDataResponse } from 'src/app/models/getuserdataresponse';
import { GeneralResponse } from 'src/app/models/generalresponse';
import { Router } from '@angular/router';

@Component({
  selector: 'app-admin-user',
  templateUrl: './admin-user.component.html',
  styleUrls: ['./admin-user.component.scss']
})
export class AdminUserComponent implements OnInit, AfterViewChecked {

  viewLoaded = false
  contentLoaded = false
  roles = ['']

  title = ''
  labelUsername = ''
  labelPassword = ''
  checkBoxUserHidden = ''
  checked: boolean = false;
  readOnlyName: boolean = false;
  disableCheckbox: boolean = false;
  id: {user: number, navigationId: number} = window.history.state;
  editBtn = ''
  labelRole = ''
  roleAdmin = ''
  roleBoss = ''
  roleEmployee = ''
  labelOvertime = ''
  labelWorkingMinutes = ''
  labelWorkingDays = ''
  labelVacationDays = ''

  constructor(
    private translate: TranslateService, 
    private location: Location,
    private dialog: MatDialog,
    private api: ApiService,
    private encrypt: EncryptionService,
    private router: Router
    ) { }

  ngOnInit(): void {

    //display spinner
    this.dialog.open(SpinnerComponent, {
      id: 'AdminUser_spinnerTranslationGlobal',
      autoFocus: false,
      disableClose: true
    });

    this.fill();

    //check if standarduser
    if(this.id.user === 1) {
      this.readOnlyName = true;
      this.disableCheckbox = true;
    }
  
    this.translate.getTranslation(this.translate.defaultLang).subscribe((translation: any) => { 

      //close spinner
      this.dialog.getDialogById('AdminUser_spinnerTranslationGlobal')?.close();

      this.title = translation.Toolbar.Title.AdminUser;
      this.labelUsername = translation.Admin.Add.LabelUsername;
      this.labelPassword = translation.Admin.Add.LabelPassword;
      this.checkBoxUserHidden = translation.Admin.Add.CheckBoxUserHidden;
      this.editBtn = translation.EditButton;
      this.labelRole = translation.Admin.Add.LabelRole;
      this.roleAdmin = translation.Admin.Add.RoleAdmin;
      this.roleBoss = translation.Admin.Add.RoleBoss;
      this.roleEmployee = translation.Admin.Add.RoleEmployee;
      this.labelOvertime = translation.Admin.Add.LabelOvertime;
      this.labelWorkingMinutes = translation.Admin.Add.LabelWorkingMinutes;
      this.labelWorkingDays = translation.Admin.Add.LabelWorkingDays;
      this.labelVacationDays = translation.Admin.Add.LabelVacationDays;
    });
  }
  
  ngAfterViewChecked(): void{
    let btn = document.getElementById('saveUserBtn')

    if(btn!.clientHeight != 0 && this.viewLoaded == false){
      let toolbar =  document.getElementsByTagName('mat-toolbar')[0].clientHeight
      let container = document.getElementById('buttonContainer')?.clientHeight
      var scrollView = document.getElementById('adminUserScrollView')
  
      let screenHeight = window.innerHeight-toolbar-container!
      scrollView!.style.height = screenHeight + 'px'

      this.viewLoaded = true
    }

    if(this.roleAdmin != '' && this.contentLoaded == false){
      this.roles = [this.roleAdmin, this.roleBoss, this.roleEmployee]
      this.contentLoaded = true
    }
  }

  checking() {
    this.checked = !this.checked;
  }

  changeUser(userName: string) {
    this.modifyUser(userName, this.checked);
  }

  private async fill() {
    let userList: [{
      id: number,
      type: number,
      name: string,
      hidden: boolean,
      overtime: number,
      weeklyWorkingMinutes: number,
      weeklyWorkingDays: number,
      yearVacationDays: number
  }] = await this.getUserData(this.id.user);
    this.userName = userList[0].name;
    this.checked = !userList[0].hidden;
  }

  private async getUserData(id: number) {

    //variables
    let publicKeyAnswer;
    let getUserDataAnswer;
    let publicKeyPromise;
    let getUserDataPromise;

    let publicKeyErrorCode: number;
    let getUserDataErrorCode: number;
    let session: string = localStorage.getItem('Session') as string;
    let sessionAsync: string;
    let publicKey: string;
    let apiKey: string = localStorage.getItem('APIKey') as string;

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

    //get userdata from api
    getUserDataAnswer = await this.api.sendPostRequest<GetUserDataResponse>(
      'users/get/', {
        apiKey: apiKey,
        session: sessionAsync,
        filter: 'id',
        value: id
      }
    );
    getUserDataPromise = await getUserDataAnswer.toPromise();
    getUserDataErrorCode = getUserDataPromise.errorCode;

    return getUserDataPromise.profiles
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
    let session: string = localStorage.getItem('Session') as string;

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
    sessionAsync = await this.encrypt.encryptTextAsync(session, publicKey);

    //modifyUser
    modifyAnswer = await this.api.sendPostRequest<GeneralResponse>(
      'users/modify/', {
        apiKey: apiKey,
        session: sessionAsync,
        user: this.id.user,
        name: userName,
        hidden: !userVisible
      }
    );
    modifyPromise = await modifyAnswer.toPromise();
    modifyErrorCode = modifyPromise.errorCode;

    this.router.navigateByUrl('/app/main/admin');
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
