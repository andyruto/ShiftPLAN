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

  constructor(public dialogRef: MatDialogRef<ProfileDialog>, private translate: TranslateService){}

  ngOnInit(): void {
    this.translate.getTranslation(this.translate.defaultLang).subscribe((translation: any) => { 
      this.logoutLabel = translation.Profile.LogoutLabel;
      this.yesBtn = translation.YesButton
      this.noBtn = translation.NoButton
    });
  }

  userLogout(): void{
    console.log("User logs out")
    this.dialogRef.close();
  }
}