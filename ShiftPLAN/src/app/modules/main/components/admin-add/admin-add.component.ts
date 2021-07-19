/**
 * admin-add.component.ts
 * 
 *  Main typescript class for the admin-add component.
 * 
 * author: Anne Naumann
 * last edit / by: 2021-07-09 / Anne Naumann
 */
import { Component, OnInit, AfterViewChecked } from '@angular/core';
import { TranslateService } from '@ngx-translate/core';
import { Location } from '@angular/common'
import { ApiService } from 'src/app/services/api.service';
import { EncryptionService } from 'src/app/services/encryption.service';
import { PublicKeyResponse } from 'src/app/models/publickeyresponse';
import { GeneralResponse } from 'src/app/models/generalresponse';
import { MatDialog } from '@angular/material/dialog';
import { SpinnerComponent } from 'src/app/modules/view-elements/spinner/spinner.component';

@Component({
  selector: 'app-admin-add',
  templateUrl: './admin-add.component.html',
  styleUrls: ['./admin-add.component.scss']
})
export class AdminAddComponent implements OnInit, AfterViewChecked {

  viewLoaded = false
  contentLoaded = false
  roles = ['']

  title = ''
  labelUsername = ''
  labelPassword = ''
  checkBoxUserHidden = ''
  saveBtn = ''
  checked: boolean = false;
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
    private api: ApiService,
    private encrypt: EncryptionService,
    public dialog : MatDialog
    ) { }

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

    if(this.roleAdmin != '' && this.contentLoaded == false){
      this.roles = [this.roleAdmin, this.roleBoss, this.roleEmployee]
      this.contentLoaded = true
    }
  }

  saveNewUser(username: string, password: string): void{
    let userVisible = document.getElementById('checkBoxUserHidden')?.classList.contains('mat-checkbox-checked')
    this.addUser(username, password);
    this.location.back()
  }

  private async addUser(userName: string, password: string) {

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
    passwordHash = await this.encrypt.convertToHash(password);

    //encrypt session and password hash asyncronous
    sessionAsync = await this.encrypt.encryptTextAsync(session, publicKey);
    passwordAsync = await this.encrypt.encryptTextAsync(passwordHash,publicKey);

    //send add user request
    addUserAnswer = await this.api.sendPostRequest<GeneralResponse>(
      'users/create/', {
        apiKey: apiKey,
        session: sessionAsync,
        name: userName,
        pwHash: passwordAsync,
        hidden: !this.checked
      }
    );
    addUserPromise = await addUserAnswer.toPromise();
    addUserErrorCode = addUserPromise.errorCode;
  }
}
