/**
 * wizard-password.component.ts
 * 
 * Main typescript class for the wizard-password component.
 * 
 * author: Anne Naumann
 * last edit / by: 2021-05-29 / Anne Naumann
 */
import { Component, OnInit } from '@angular/core';
import { TranslateService } from '@ngx-translate/core';

@Component({
  selector: 'app-wizard-password',
  templateUrl: './wizard-password.component.html',
  styleUrls: ['./wizard-password.component.scss']
})
export class WizardPasswordComponent implements OnInit{

  title = ''
  labelPasswordOld = ''
  labelPasswordNew = ''
  labelPasswordCheck = ''
  warningOld = ''
  warningNew = ''
  nextButton = ''

  constructor(private translate : TranslateService) { }
  ngOnInit(): void {
    this.translate.getTranslation(this.translate.defaultLang).subscribe((translation: any) => { 
      this.title = translation.WizardPassword.Title;
      this.labelPasswordOld = translation.WizardPassword.LabelPasswordOld;
      this.labelPasswordNew = translation.WizardPassword.LabelPasswordNew;
      this.labelPasswordCheck = translation.WizardPassword.LabelPasswordCheck;
      this.warningOld = translation.WizardPassword.WarningOld;
      this.warningNew = translation.WizardPassword.WarningNew;
      this.nextButton = translation.NextButton;
    });
  }

  checkPassword(password: string): void{
    //TODO: get initial password
    let initPassword: string = ''
    let checkDiv = document.getElementById('checkOldPassword')
    let setDiv = document.getElementById('setNewPassword')
    let warning = document.getElementById('warningOld')

    if(initPassword == password){
      checkDiv!.style.display = 'none'
      setDiv!.style.display = 'block'
    }else {
      warning!.style.visibility = 'visible'
    }
  }

  setPassword(password: string, passwordCheck: string): void{
    let warning = document.getElementById('warningNew')
    var inputValid: Boolean = false

    //check if password secure enough?
    console.log(password)
    console.log(passwordCheck)

    if(password == passwordCheck){
      //navigate to next component
    }else{
      warning!.style.visibility = 'visible'
    }
  }
}
