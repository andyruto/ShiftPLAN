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
import { Location } from '@angular/common'

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

  constructor(private translate: TranslateService, private location: Location) { }

  ngOnInit(): void {
    this.translate.getTranslation(this.translate.defaultLang).subscribe((translation: any) => { 
      this.title = translation.Toolbar.Title.AdminUser;
      this.labelUsername = translation.Admin.Add.LabelUsername;
      this.labelPassword = translation.Admin.Add.LabelPassword;
      this.checkBoxUserHidden = translation.Admin.Add.CheckBoxUserHidden;
      this.saveBtn = translation.SaveButton;
    });
  }
}
