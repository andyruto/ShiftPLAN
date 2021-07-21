/**
 * settings-info.component.ts
 * 
 *  Main typescript class for the settings-info component.
 * 
 * author: Anne Naumann
 * last edit / by: 2021-06-07 / Anne Naumann
 */
import { Component, OnInit } from '@angular/core';
import { MatDialog } from '@angular/material/dialog';
import { TranslateService } from '@ngx-translate/core';
import { SpinnerComponent } from 'src/app/modules/view-elements/spinner/spinner.component';

@Component({
  selector: 'app-settings-info',
  templateUrl: './settings-info.component.html',
  styleUrls: ['./settings-info.component.scss']
})
export class SettingsInfoComponent implements OnInit {

  title = ''

  constructor(private translate: TranslateService, public dialog: MatDialog) { }
  ngOnInit(): void {

    //display spinner
    this.dialog.open(SpinnerComponent, {
      id: 'SettingsInfo_spinnerTranslationGlobal',
      autoFocus: false,
      disableClose: true
    });

    this.translate.getTranslation(this.translate.defaultLang).subscribe((translation: any) => { 

      //close spinner
      this.dialog.getDialogById('SettingsInfo_spinnerTranslationGlobal')?.close();

      this.title = translation.Settings.LabelInfo;
    });
  }
}
