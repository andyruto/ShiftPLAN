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

@Component({
  selector: 'app-wizard-permission',
  templateUrl: './wizard-permission.component.html',
  styleUrls: ['./wizard-permission.component.scss']
})
export class WizardPermissionComponent implements OnInit {

  title = ''
  
  constructor(private translate : TranslateService) { }
  ngOnInit(): void {
    this.translate.getTranslation(this.translate.defaultLang).subscribe((translation: any) => { 
      this.title = translation.WizardPermission.Title;
    });
  }

}
