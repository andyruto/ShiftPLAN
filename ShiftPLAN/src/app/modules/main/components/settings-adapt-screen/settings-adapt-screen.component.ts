/**
 * settings-adapt-screen.component.ts
 * 
 *  Main typescript class for the settings-adapt-screen component.
 * 
 * author: Anne Naumann
 * last edit / by: 2021-06-09 / Anne Naumann
 */
import { Component, OnInit } from '@angular/core';
import { TranslateService } from '@ngx-translate/core';

@Component({
  selector: 'app-settings-adapt-screen',
  templateUrl: './settings-adapt-screen.component.html',
  styleUrls: ['./settings-adapt-screen.component.scss']
})
export class SettingsAdaptScreenComponent implements OnInit {

  title = ''
  statisticsLabel = ''

  constructor(private translate: TranslateService) { }
  ngOnInit(): void {
    this.translate.getTranslation(this.translate.defaultLang).subscribe((translation: any) => { 
      this.title = translation.Settings.LabelAdapt;
      this.statisticsLabel = translation.Settings.Adapt.LabelStatistic;
    });
  }
}
