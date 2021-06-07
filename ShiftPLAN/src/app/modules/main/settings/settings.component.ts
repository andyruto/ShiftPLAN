/**
 * settings.component.ts
 * 
 *  Main typescript class for the settings component.
 * 
 * author: Anne Naumann
 * last edit / by: 2021-06-07 / Anne Naumann
 */
import { Component, OnInit } from '@angular/core';
import { TranslateService } from '@ngx-translate/core';

@Component({
  selector: 'app-settings',
  templateUrl: './settings.component.html',
  styleUrls: ['./settings.component.scss']
})
export class SettingsComponent implements OnInit {

  title = ''

  constructor(private translate: TranslateService) { }
  ngOnInit(): void {
    this.translate.getTranslation(this.translate.defaultLang).subscribe((translation: any) => { 
      this.title = translation.Toolbar.Title.Settings;
    });
  }

  navigateToTheme(): void {
    console.log('click on theme')
  }

  navigateToAdaptHome(): void {
    console.log('click on adapt')
  }

  navigateToPassword(): void {
    console.log('click on password')
  }

  navigateToInfo(): void {
    console.log('click on info')
  }
}
