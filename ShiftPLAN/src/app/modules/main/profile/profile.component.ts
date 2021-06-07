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

@Component({
  selector: 'app-profile',
  templateUrl: './profile.component.html',
  styleUrls: ['./profile.component.scss']
})
export class ProfileComponent implements OnInit {

  title = ''
  username = ''
  role = ''
  department = ''
  departmentHead = ''
  logoutLabel = ''
  yesBtn = ''
  noBtn = ''

  constructor(private translate: TranslateService) { }
  ngOnInit(): void {
    this.translate.getTranslation(this.translate.defaultLang).subscribe((translation: any) => { 
      this.title = translation.Toolbar.Title.Profile;
      this.username = translation.Profile.Username;
      this.role = translation.Profile.Role;
      this.department = translation.Profile.Department;
      this.departmentHead = translation.Profile.DepartmentHead;
      this.logoutLabel = translation.Profile.LogoutLabel;
      this.yesBtn = translation.YesButton
      this.noBtn = translation.NoButton
    });
  }

  checkLogout(): void{
    let checkDiv = document.getElementById('checkLogout')
    checkDiv!.style.display = 'block'
  }

  stopLogout(): void{
    let checkDiv = document.getElementById('checkLogout')
    checkDiv!.style.display = 'none'
  }

  userLogout(): void{
    console.log("User logs out")
  }
}
