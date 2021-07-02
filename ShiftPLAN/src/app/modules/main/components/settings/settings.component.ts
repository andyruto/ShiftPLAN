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
import { Router } from '@angular/router'
import { UsertypeService } from 'src/app/services/usertype.service';
import { SpinnerComponent } from 'src/app/modules/view-elements/spinner/spinner.component';
import { MatDialog } from '@angular/material/dialog';

@Component({
  selector: 'app-settings',
  templateUrl: './settings.component.html',
  styleUrls: ['./settings.component.scss']
})
export class SettingsComponent implements OnInit {

  title = ''
  labelTheme = ''
  labelAdapt = ''
  labelPassword = ''
  labelInfo = ''
  admin: boolean = false

  constructor(
    private translate: TranslateService, 
    private router : Router,
    private usertype : UsertypeService, 
    public dialog: MatDialog) { }

  ngOnInit(): void {

    //display spinner
    this.dialog.open(SpinnerComponent, {
      id: 'Settings_spinnerTranslationGlobal',
      autoFocus: false,
      disableClose: true
    });

    this.translate.getTranslation(this.translate.defaultLang).subscribe((translation: any) => { 

      //close spinner
      this.dialog.getDialogById('Settings_spinnerTranslationGlobal')?.close();

      this.title = translation.Toolbar.Title.Settings;
      this.labelTheme = translation.Settings.LabelTheme;
      this.labelAdapt = translation.Settings.LabelAdapt;
      this.labelPassword = translation.Settings.LabelPassword;
      this.labelInfo = translation.Settings.LabelInfo;
    });

    this.checkBtn();
  }

  private async checkBtn() {
    this.admin = (await this.usertype.getShown()).admin;
  }

  navigateToTheme(): void {
    this.router.navigate(['/app/settings-theme'])
  }

  navigateToAdaptHome(): void {
    this.router.navigate(['/app/settings-adapt-screen'])
  }

  navigateToPassword(): void {
    this.router.navigate(['/app/settings-password'])
  }

  navigateToInfo(): void {
    this.router.navigate(['/app/settings-info'])
  }
}
