/**
 * settings-password.component.ts
 * 
 *  Main typescript class for the settings-password component.
 * 
 * author: Anne Naumann
 * last edit / by: 2021-06-07 / Anne Naumann
 */
import { Component, OnInit } from '@angular/core';
import { TranslateService } from '@ngx-translate/core';

@Component({
  selector: 'app-settings-password',
  templateUrl: './settings-password.component.html',
  styleUrls: ['./settings-password.component.scss']
})
export class SettingsPasswordComponent implements OnInit {

  title = ''

  constructor(private translate: TranslateService) { }
  ngOnInit(): void {
    this.translate.getTranslation(this.translate.defaultLang).subscribe((translation: any) => { 
      this.title = translation.Settings.LabelPassword;
    });
  }
}
