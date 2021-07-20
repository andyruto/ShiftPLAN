/**
 * admin.component.ts
 * 
 *  Main typescript class for the admin component.
 * 
 * author: Anne Naumann
 * last edit / by: 2021-06-11 / Anne Naumann
 */
import { Component, OnInit, AfterViewInit, HostListener } from '@angular/core';
import { TranslateService } from '@ngx-translate/core';
import { Router } from '@angular/router'
import { PublicKeyResponse } from 'src/app/models/publickeyresponse';
import { ApiService } from 'src/app/services/api.service';
import { EncryptionService } from 'src/app/services/encryption.service';
import { GetUsersResponse } from 'src/app/models/getusersresponse';
import { MatDialog } from '@angular/material/dialog';
import { SpinnerComponent } from 'src/app/modules/view-elements/spinner/spinner.component';

@Component({
  selector: 'app-admin',
  templateUrl: './admin.component.html',
  styleUrls: ['./admin.component.scss']
})
export class AdminComponent implements OnInit, AfterViewInit{

  title = ''
  tabUsers = ''
  labelKey = ''
  apiKey: string = localStorage.getItem('APIKey') as string;

  users: [{id: number, name: string}] = [{id: 1,name: ''}];

  constructor(
    private translate: TranslateService, 
    private router : Router,
    private api : ApiService,
    private encrypt: EncryptionService,
    public dialog : MatDialog
    ) {}

  ngOnInit(): void {

    //display spinner
    this.dialog.open(SpinnerComponent, {
      id: 'Admin_spinnerTranslationGlobal',
      autoFocus: false,
      disableClose: true
    });

    this.refreshUsers();

    this.translate.getTranslation(this.translate.defaultLang).subscribe((translation: any) => { 

      //close spinner
      this.dialog.getDialogById('Admin_spinnerTranslationGlobal')?.close();

      this.title = translation.Toolbar.Title.Admin;
      this.tabUsers = translation.Admin.TabUser;
      this.labelKey = translation.Admin.LabelApiKey;
    });
  }

  ngAfterViewInit(): void{
    this.setScreenSize();
  }

  @HostListener('window:resize', ['$event'])
  setScreenSize() {
    let toolbar =  document.getElementsByTagName('mat-toolbar')[0]
    let bottomBar = document.getElementsByTagName('app-bottom-bar')[0]
    let header = document.getElementsByTagName('mat-tab-header')[0]
    var scrollView = document.getElementById('userScrollView')

    let screenHeight = window.innerHeight-toolbar.clientHeight-bottomBar.clientHeight-header.clientHeight
    scrollView!.style.height = screenHeight + 'px'
  }

  ngOnDestroy() {
    this.dialog.closeAll();
  }

  navigateToUser(user: string): void{

    //display spinner
    this.dialog.open(SpinnerComponent, {
      id: 'Admin_spinnerNavigate_admin-user',
      autoFocus: false,
      disableClose: true
    });

    this.router.navigateByUrl('/app/admin-user', {state: { user }})
  }

  navigateToAddUser(): void{

    //display spinner
    this.dialog.open(SpinnerComponent, {
      id: 'Admin_spinnerNavigate_admin-add',
      autoFocus: false,
      disableClose: true
    });

    this.router.navigate(['/app/admin-add'])
  }

  private async refreshUsers() {

    //display spinner
    this.dialog.open(SpinnerComponent, {
      id: 'Admin_spinnerRefresh',
      autoFocus: false,
      disableClose: true
    });

    //variables
    let publicKeyAnswer;
    let getusersAnswer;
    let getInvUsersAnswer;
    let publicKeyPromise;
    let getusersPromise;
    let getInvUsersPromise;
        
    let publicKeyErrorCode: number;
    let getusersErrorCode: number;
    let getInvUsersErrorCode: number;
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
    sessionAsync = await this.encrypt.encryptTextAsync(session, publicKey);

    //get visibel users from api
    getusersAnswer = await this.api.sendPostRequest<GetUsersResponse>(
      'users/get/', {
        apiKey: this.apiKey,
        session: sessionAsync,
        filter: 'all',
        value: 'visible'
      }
    );
    getusersPromise = await getusersAnswer.toPromise();
    getusersErrorCode = getusersPromise.errorCode;

    //get invisibel users from api
    getInvUsersAnswer = await this.api.sendPostRequest<GetUsersResponse>(
      'users/get/', {
        apiKey: this.apiKey,
        session: sessionAsync,
        filter: 'all',
        value: 'invisible'
      }
    );
    getInvUsersPromise = await getInvUsersAnswer.toPromise();
    getInvUsersErrorCode = getInvUsersPromise.errorCode;

    //addUsersToUI
    this.users = getusersPromise.profiles;
    if(getInvUsersPromise.profiles) {
      this.users.push(...getInvUsersPromise.profiles);
    }

    //close spinner
    this.dialog.getDialogById('Admin_spinnerRefresh')?.close();
  }
}
