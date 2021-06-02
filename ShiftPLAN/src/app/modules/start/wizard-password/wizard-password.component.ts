/**
 * wizard-password.component.ts
 * 
 * Main typescript class for the wizard-password component.
 * 
 * author: Anne Naumann
 * last edit / by: 2021-06-02 / Anne Naumann
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
  labelPasswordNew = ''
  labelPasswordCheck = ''
  warningNew = ''
  nextButton = ''

  constructor(private translate : TranslateService) { }
  ngOnInit(): void {
    this.translate.getTranslation(this.translate.defaultLang).subscribe((translation: any) => { 
      this.title = translation.WizardPassword.Title;
      this.labelPasswordNew = translation.WizardPassword.LabelPasswordNew;
      this.labelPasswordCheck = translation.WizardPassword.LabelPasswordCheck;
      this.warningNew = translation.WizardPassword.WarningNew;
      this.nextButton = translation.NextButton;
    });
  }

  setPassword(password: string, passwordCheck: string): void{
    var inputValid: Boolean = false

    //check if password secure enough?
    console.log(password)
    console.log(passwordCheck)

    if(password == passwordCheck){
      //navigate to next component
    }else{
      //display warning
    }
  }
}
