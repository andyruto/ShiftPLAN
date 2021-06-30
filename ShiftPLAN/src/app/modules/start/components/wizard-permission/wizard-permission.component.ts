/**
 * wizard-permission.component.ts
 * 
 * Main typescript class for the wizard-permission component.
 * 
 * author: Anne Naumann
 * last edit / by: 2021-05-29 / Anne Naumann
 */
import { Component, OnInit } from '@angular/core';
import { TranslateService } from '@ngx-translate/core';
import { Router } from '@angular/router';

@Component({
  selector: 'app-wizard-permission',
  templateUrl: './wizard-permission.component.html',
  styleUrls: ['./wizard-permission.component.scss']
})
export class WizardPermissionComponent implements OnInit {

  title = ''
  
  constructor(
    private translate : TranslateService,
    private router : Router) { }
  ngOnInit(): void {
    this.translate.getTranslation(this.translate.defaultLang).subscribe((translation: any) => { 
      this.title = translation.WizardPermission.Title;
    });
    this.router.navigate(['start/login']);
  }

}
