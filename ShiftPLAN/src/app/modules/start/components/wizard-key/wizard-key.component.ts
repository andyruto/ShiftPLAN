/**
 * wizard-key.component.ts
 * 
 * Main typescript class for the wizard-key component.
 * 
 * author: Anne Naumann
 * last edit / by: 2021-06-30 / Anne Naumann
 */
import { Component, OnInit } from '@angular/core';
import { Router } from '@angular/router';
import { TranslateService } from '@ngx-translate/core';
import { GeneralResponse } from 'src/app/models/generalresponse';
import { ApiService } from 'src/app/services/api.service';
import { MatDialog, MatDialogRef } from '@angular/material/dialog';
import { SpinnerComponent } from 'src/app/modules/view-elements/spinner/spinner.component';

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
  
  constructor(
    private translate : TranslateService, 
    private api : ApiService, 
    private router : Router,
    public dialog : MatDialog) { }

  ngOnInit(): void {

    //display spinner
    this.dialog.open(SpinnerComponent, {
      id: 'WizardKey_spinnerTranslationGlobal',
      autoFocus: false,
      disableClose: true
    });

    this.translate.getTranslation(this.translate.defaultLang).subscribe((translation: any) => { 

      //close spinner
      this.dialog.getDialogById('WizardKey_spinnerTranslationGlobal')?.close();

      this.title = translation.WizardKey.Title;
      this.label = translation.WizardKey.Label;
      this.warning = translation.WizardKey.Warning;
      this.nextButton = translation.NextButton;
    });

    this.checkURLAdress();
  }

  checkInput(userInput: string): void{
    localStorage.setItem('APIKey', userInput);
    this.checkURLAdress();
  }

  checkURLAdress() {

    //display spinner
    this.dialog.open(SpinnerComponent, {
      id: 'WizardKey_spinnerCall',
      autoFocus: false,
      disableClose: true
    });
        
    let apiKeyTemp = localStorage.getItem('APIKey');
    if(apiKeyTemp) {
      this.api.sendPostRequest<GeneralResponse>(
        'key/check/', 
        {apiKey: apiKeyTemp}
      ).then((response) => {
        response.subscribe(answer => {
          if(answer.errorCode === 0) {

            //close spinner
            this.dialog.closeAll();

            this.router.navigate(['start/wizardPermission']);
          }else {
            
            //close spinner
            this.dialog.getDialogById('WizardKey_spinnerCall')?.close();

            this.dialog.open(KeyDialog, {
              autoFocus: false
            });
          }
        })
      })
    }
    //close spinner
    this.dialog.getDialogById('WizardKey_spinnerCall')?.close();
  }
}

@Component({
  selector: 'key-dialog',
  templateUrl: 'dialog.html',
  styleUrls: ['./wizard-key.component.scss'],
})
export class KeyDialog {
  warning = '';
  ok = '';

  constructor(public dialogRef: MatDialogRef<KeyDialog>, private translation: TranslateService, public dialog : MatDialog) {}

  ngOnInit(): void {

    //display spinner
    this.dialog.open(SpinnerComponent, {
      id: 'KeyDialog_spinner',
      autoFocus: false,
      disableClose: true
    });

    this.translation.getTranslation(this.translation.defaultLang).subscribe((translation: any) => {

    //close spinner
    this.dialog.getDialogById('KeyDialog_spinner')?.close();

    this.warning = translation.WizardKey.Key;
    this.ok = translation.WizardKey.Ok;
    });
  }
}