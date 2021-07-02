/**
 * settings-password.component.ts
 * 
 *  Main typescript class for the settings-password component.
 * 
 * author: Anne Naumann
 * last edit / by: 2021-06-09 / Anne Naumann
 */
import { Component, OnInit } from '@angular/core';
import { TranslateService } from '@ngx-translate/core';
import { Location } from '@angular/common'
import { SpinnerComponent } from 'src/app/modules/view-elements/spinner/spinner.component';
import { MatDialog } from '@angular/material/dialog';

@Component({
  selector: 'app-settings-password',
  templateUrl: './settings-password.component.html',
  styleUrls: ['./settings-password.component.scss']
})
export class SettingsPasswordComponent implements OnInit {

  title = ''
  nextBtn = ''
  saveBtn = ''
  labelPassword = ''
  labelNewPassword = ''
  labelCheckPassword = ''
  warningCheck = ''
  warningSet = ''

  constructor(private translate: TranslateService, private location: Location, public dialog: MatDialog) { }
  ngOnInit(): void {

    //display spinner
    this.dialog.open(SpinnerComponent, {
      id: 'SettingsPassword_spinnerTranslationGlobal',
      autoFocus: false,
      disableClose: true
    });

    this.translate.getTranslation(this.translate.defaultLang).subscribe((translation: any) => { 

      //close spinner
      this.dialog.getDialogById('SettingsPassword_spinnerTranslationGlobal')?.close();

      this.title = translation.Settings.LabelPassword;
      this.nextBtn = translation.NextButton;
      this.saveBtn = translation.SaveButton;
      this.labelPassword = translation.Settings.Password.LabelPassword;
      this.labelNewPassword = translation.Settings.Password.LabelNewPassword;
      this.labelCheckPassword = translation.Settings.Password.LabelCheckPassword;
      this.warningCheck = translation.Settings.Password.WarningCheck;
      this.warningSet = translation.Settings.Password.WarningSet;
    });
  }

  showSetPassword(inputPassword: string) : void{
    //get real password
    let password = ''
    let checkDiv = document.getElementById('checkPassword')
    let setDiv = document.getElementById('setPassword')

    if(inputPassword == password){
      checkDiv!.style.display = 'none'
      setDiv!.style.display = 'block'
    }else {
      //display warning
    }
  }

  setNewPassword(newPassword: string, checkPassword: string): void{
    if(newPassword == checkPassword){
      //save password
      this.location.back()
    }else {
      //display warning
    }
  }
}
