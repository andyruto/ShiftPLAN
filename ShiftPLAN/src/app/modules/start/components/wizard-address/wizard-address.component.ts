/**
 * wizard-address.component.ts
 * 
 * Main typescript class for the wizard-address component.
 * 
 * author: Anne Naumann
 * last edit / by: 2021-06-30 / Anne Naumann
 */
import { Component, OnInit } from '@angular/core';
import { TranslateService } from '@ngx-translate/core';
import { ApiKeyResponse } from 'src/app/models/apikeyresponse';
import { ApiService } from 'src/app/services/api.service';
import { Router } from '@angular/router';
import { MatDialog, MatDialogRef } from '@angular/material/dialog';

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
  
  constructor(
    private translate : TranslateService, 
    private api : ApiService, 
    private router : Router, 
    public dialog : MatDialog) { }

  ngOnInit(): void {
    this.translate.getTranslation(this.translate.defaultLang).subscribe((translation: any) => { 
      this.title = translation.WizardAddress.Title;
      this.label = translation.WizardAddress.Label;
      this.warning = translation.WizardAddress.Warning;
      this.nextButton = translation.NextButton;
    });

    this.checkURLAdress();
  }
  
  checkInput(userInput: string): void {
    if(userInput === '') {
      this.dialog.open(NoInputDialog, {
        autoFocus: false
      });
    } else {
      localStorage.setItem('URLAdress', userInput);
    for(var counter: number = 0; counter < 10; counter++) {
      if(localStorage.getItem('URLAdress') === userInput) {
        break;
      }
      setTimeout(() => {}, 100);
    }
    this.checkURLAdress();
    }
  }

  checkURLAdress() {

    let urlAdress = localStorage.getItem('URLAdress');
    if(urlAdress) {
      this.api.sendPostRequest<ApiKeyResponse>('', Object()).then(
        (response) => {
          response.subscribe(answer => {
            if(answer.errorCode === 0) {
              if(answer.apiKey) {
                localStorage.setItem('APIKey', answer.apiKey);
                this.router.navigate(['start/wizardPassword']);
              }else {
                this.router.navigate(['start/wizardKey'])
              }
            }else {
              //TODOO
            }
          }, error => {
            if(error == 404) {
              this.dialog.open(Error404Dialog, {
                autoFocus: false
              });
            }else {
              this.dialog.open(ErrorDialog, {
                autoFocus: false
              });
            }
          })
        }
      )
    }else {
      const parsedUrl = new URL(window.location.href);
      const baseUrl = parsedUrl.origin + '/api/';
      localStorage.setItem('URLAdress', baseUrl);
      this.api.sendPostRequest<ApiKeyResponse>('', Object()).then(
        (response) => {
          response.subscribe(answer => {
            if(answer.errorCode === 0) {
              if(answer.apiKey) {
                localStorage.setItem('APIKey', answer.apiKey);
                this.router.navigate(['start/wizardPassword']);
              }else {
                this.router.navigate(['start/wizardKey'])
              }
            }else {
              //TODOO
            }
          }, error => {
            if(error == 404) {
              this.dialog.open(SameOriginDialog, {
                autoFocus: false
              });
            }else {
              this.dialog.open(ErrorDialog, {
                autoFocus: false
              });
            }
          })
        }
      )
    }
  }
}

@Component({
  selector: 'error-404-dialog',
  templateUrl: 'dialog.html',
  styleUrls: ['./wizard-address.component.scss'],
})
export class Error404Dialog {
  warning = '';
  ok = '';

  constructor(public dialogRef: MatDialogRef<Error404Dialog>, private translation: TranslateService) {}

  ngOnInit(): void {
    this.translation.getTranslation(this.translation.defaultLang).subscribe((translation: any) => {
    this.warning = translation.WizardAddress.Error404;
    this.ok = translation.WizardAddress.Ok;
    });
  }
}

@Component({
  selector: 'error-dialog',
  templateUrl: 'dialog.html',
  styleUrls: ['./wizard-address.component.scss'],
})
export class ErrorDialog {
  warning = '';
  ok = '';

  constructor(public dialogRef: MatDialogRef<ErrorDialog>, private translation: TranslateService) {}

  ngOnInit(): void {
    this.translation.getTranslation(this.translation.defaultLang).subscribe((translation: any) => {
    this.warning = translation.WizardAddress.Error;
    this.ok = translation.WizardAddress.Ok;
    });
  }
}

@Component({
  selector: 'same-origin-dialog',
  templateUrl: 'dialog.html',
  styleUrls: ['./wizard-address.component.scss'],
})
export class SameOriginDialog {
  warning = '';
  ok = '';

  constructor(public dialogRef: MatDialogRef<SameOriginDialog>, private translation: TranslateService) {}

  ngOnInit(): void {
    this.translation.getTranslation(this.translation.defaultLang).subscribe((translation: any) => {
    this.warning = translation.WizardAddress.SameOrigin;
    this.ok = translation.WizardAddress.Ok;
    });
  }
}

@Component({
  selector: 'no-input-dialog',
  templateUrl: 'dialog.html',
  styleUrls: ['./wizard-address.component.scss'],
})
export class NoInputDialog {
  warning = '';
  ok = '';

  constructor(public dialogRef: MatDialogRef<NoInputDialog>, private translation: TranslateService) {}

  ngOnInit(): void {
    this.translation.getTranslation(this.translation.defaultLang).subscribe((translation: any) => {
    this.warning = translation.WizardAddress.NoInput;
    this.ok = translation.WizardAddress.Ok;
    });
  }
}