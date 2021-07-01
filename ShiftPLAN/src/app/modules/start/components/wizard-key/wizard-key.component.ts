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
    this.translate.getTranslation(this.translate.defaultLang).subscribe((translation: any) => { 
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
    let apiKeyTemp = localStorage.getItem('APIKey');
    if(apiKeyTemp) {
      this.api.sendPostRequest<GeneralResponse>(
        'key/check/', 
        {apiKey: apiKeyTemp}
      ).then((response) => {
        response.subscribe(answer => {
          if(answer.errorCode === 0) {
            this.router.navigate(['start/wizardPermission']);
          }else {
            this.dialog.open(KeyDialog, {
              autoFocus: false
            });
          }
        })
      })
    }

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

  constructor(public dialogRef: MatDialogRef<KeyDialog>, private translation: TranslateService) {}

  ngOnInit(): void {
    this.translation.getTranslation(this.translation.defaultLang).subscribe((translation: any) => {
    this.warning = translation.WizardKey.Key;
    this.ok = translation.WizardKey.Ok;
    });
  }
}