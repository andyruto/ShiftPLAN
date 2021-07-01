/**
 * profile.component.ts
 * 
 *  Main typescript class for the profile component.
 * 
 * author: Anne Naumann
 * last edit / by: 2021-06-07 / Anne Naumann
 */
import { Component, OnInit } from '@angular/core';
import { TranslateService } from '@ngx-translate/core';
import { MatDialog, MatDialogRef } from '@angular/material/dialog';
import { ApiService } from 'src/app/services/api.service';
import { GeneralResponse } from 'src/app/models/generalresponse';
import { Router } from '@angular/router';
import { EncryptionService } from 'src/app/services/encryption.service';
import { PublicKeyResponse } from 'src/app/models/publickeyresponse';

@Component({
  selector: 'app-profile',
  templateUrl: './profile.component.html',
  styleUrls: ['./profile.component.scss']
})
export class ProfileComponent implements OnInit {

  title = ''
  username = ''
  role = ''

  constructor(private translate: TranslateService, public dialog: MatDialog) { }
  ngOnInit(): void {
    this.translate.getTranslation(this.translate.defaultLang).subscribe((translation: any) => { 
      this.title = translation.Toolbar.Title.Profile;
      this.username = translation.Profile.Username;
      this.role = translation.Profile.Role;
    });
  }

  checkLogout(): void{
    this.dialog.open(ProfileDialog, {
      autoFocus: false,
    });
  }
}

@Component({
  selector: 'profile-dialog',
  templateUrl: 'profile-dialog.html',
  styleUrls: ['./profile.component.scss']
})
export class ProfileDialog {

  logoutLabel = ''
  yesBtn = ''
  noBtn = '' 

  constructor(
    public dialogRef: MatDialogRef<ProfileDialog>, 
    private translate: TranslateService,
    private api : ApiService,
    private router : Router,
    private encrypt : EncryptionService){}

  ngOnInit(): void {
    this.translate.getTranslation(this.translate.defaultLang).subscribe((translation: any) => { 
      this.logoutLabel = translation.Profile.LogoutLabel;
      this.yesBtn = translation.YesButton
      this.noBtn = translation.NoButton
    });
  }

  userLogout(): void{

    this.logout().then( _ => {
      this.dialogRef.close();
      this.router.navigate(['start/login']);
    });
  }

  private async logout() {

    //variables
    let publicKeyAnswer;
    let logoutAnswer;
    let publicKeyPromise;
    let logoutPromise;

    let publicKeyErrorCode: number;
    let logoutErrorCode: number;
    let apiKey = localStorage.getItem('APIKey');
    let publicKey: string;
    let session: string = localStorage.getItem('Session') as string;
    let sessionAsync: string;

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

    //send logout post-request
    logoutAnswer = await this.api.sendPostRequest<GeneralResponse>(
      'logout/', {
        apiKey: apiKey,
        session: sessionAsync
      }
    );
    logoutPromise = await logoutAnswer.toPromise();
    logoutErrorCode = logoutPromise.errorCode;
  }
}