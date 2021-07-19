/**
 * settings-adapt-screen.component.ts
 * 
 *  Main typescript class for the settings-adapt-screen component.
 * 
 * author: Anne Naumann
 * last edit / by: 2021-06-09 / Anne Naumann
 */
import { Component, OnInit } from '@angular/core';
import { MatDialog } from '@angular/material/dialog';
import { TranslateService } from '@ngx-translate/core';
import { SpinnerComponent } from 'src/app/modules/view-elements/spinner/spinner.component';

@Component({
  selector: 'app-settings-adapt-screen',
  templateUrl: './settings-adapt-screen.component.html',
  styleUrls: ['./settings-adapt-screen.component.scss']
})
export class SettingsAdaptScreenComponent implements OnInit {

  title = ''
  statisticsLabel = ''

  constructor(private translate: TranslateService, public dialog: MatDialog) { }
  ngOnInit(): void {

    //display spinner
    this.dialog.open(SpinnerComponent, {
      id: 'SettingsAdaptScreen_spinnerTranslationGlobal',
      autoFocus: false,
      disableClose: true
    });

    this.translate.getTranslation(this.translate.defaultLang).subscribe((translation: any) => { 

      //close spinner
      this.dialog.getDialogById('SettingsAdaptScreen_spinnerTranslationGlobal')?.close();

      this.title = translation.Settings.LabelAdapt;
      this.statisticsLabel = translation.Settings.Adapt.LabelStatistic;
    });
  }
}
