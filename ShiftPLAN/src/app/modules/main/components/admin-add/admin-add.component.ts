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
  labelRole = ''
  roleAdmin = ''
  roleBoss = ''
  roleEmployee = ''
  labelOvertime = ''
  labelWorkingMinutes = ''
  labelWorkingDays = ''
  labelVacationDays = ''

  constructor(private translate: TranslateService, private location: Location) { }

  ngOnInit(): void {
    this.translate.getTranslation(this.translate.defaultLang).subscribe((translation: any) => { 
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
     //save user
    this.location.back()
  }
}
