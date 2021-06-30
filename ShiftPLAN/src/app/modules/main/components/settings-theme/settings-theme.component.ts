/**
 * settings-theme.component.ts
 * 
 *  Main typescript class for the settings-theme component.
 * 
 * author: Anne Naumann
 * last edit / by: 2021-06-07 / Anne Naumann
 */
import { Component, OnInit } from '@angular/core';
import { TranslateService } from '@ngx-translate/core';

@Component({
  selector: 'app-settings-theme',
  templateUrl: './settings-theme.component.html',
  styleUrls: ['./settings-theme.component.scss']
})
export class SettingsThemeComponent implements OnInit {

  title = ''
  labelMode = ''
  lightBtn = ''
  darkBtn = ''

  constructor(private translate: TranslateService) { }
  ngOnInit(): void {
    this.translate.getTranslation(this.translate.defaultLang).subscribe((translation: any) => { 
      this.title = translation.Settings.LabelTheme;
      this.labelMode = translation.Settings.Theme.LabelMode;
      this.lightBtn = translation.Settings.Theme.LightButton;
      this.darkBtn = translation.Settings.Theme.DarkButton;
    });
  }
}
