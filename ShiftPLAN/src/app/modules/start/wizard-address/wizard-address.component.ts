/**
 * wizard-address.component.ts
 * 
 * Main typescript class for the wizard-address component.
 * 
 * author: Anne Naumann
 * last edit / by: 2021-05-29 / Anne Naumann
 */
import { Component, OnInit } from '@angular/core';
import { TranslateService } from '@ngx-translate/core';

@Component({
  selector: 'app-wizard-address',
  templateUrl: './wizard-address.component.html',
  styleUrls: ['./wizard-address.component.scss']
})
export class WizardAddressComponent implements OnInit {

  title = ''
  label = ''
  warning = ''
  nextButton = ''
  
  constructor(private translate : TranslateService) { }
  ngOnInit(): void {
    this.translate.getTranslation(this.translate.defaultLang).subscribe((translation: any) => { 
      this.title = translation.WizardAddress.Title;
      this.label = translation.WizardAddress.Label;
      this.warning = translation.WizardAddress.Warning;
      this.nextButton = translation.NextButton;
    });
   }
  
  checkInput(userInput: string): void{
    let warning = document.getElementById('warning')
    var inputValid: Boolean = false

    //check Input
    console.log(userInput)

    if(inputValid){
      //navigate to next component
    }else{
      warning!.style.visibility = 'visible'
    }
  }
}
