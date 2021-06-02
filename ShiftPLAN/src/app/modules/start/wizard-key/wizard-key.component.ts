/**
 * wizard-key.component.ts
 * 
 * Main typescript class for the wizard-key component.
 * 
 * author: Anne Naumann
 * last edit / by: 2021-06-02 / Anne Naumann
 */
import { Component, OnInit } from '@angular/core';
import { TranslateService } from '@ngx-translate/core';

@Component({
  selector: 'app-wizard-key',
  templateUrl: './wizard-key.component.html',
  styleUrls: ['./wizard-key.component.scss']
})
export class WizardKeyComponent implements OnInit {

  title = ''
  label = ''
  warning = ''
  nextButton = ''
  
  constructor(private translate : TranslateService) { }
  ngOnInit(): void {
    this.translate.getTranslation(this.translate.defaultLang).subscribe((translation: any) => { 
      this.title = translation.WizardKey.Title;
      this.label = translation.WizardKey.Label;
      this.warning = translation.WizardKey.Warning;
      this.nextButton = translation.NextButton;
    });
  }

  checkInput(userInput: string): void{
    var inputValid: Boolean = false

    //check Input
    console.log(userInput)

    if(inputValid){
      //navigate to next component
    }else{
      //display warning?
    }
  }
}
