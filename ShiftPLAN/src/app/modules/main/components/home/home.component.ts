/**
 * home.component.ts
 * 
 *  Main typescript class for the home component.
 * 
 * author: Anne Naumann
 * last edit / by: 2021-06-30 / Anne Naumann
 */
import { Component, OnInit } from '@angular/core';
import { CheckboxControlValueAccessor } from '@angular/forms';
import { MatDialog } from '@angular/material/dialog';
import { TranslateService } from '@ngx-translate/core';
import { SpinnerComponent } from 'src/app/modules/view-elements/spinner/spinner.component';
import { UsertypeService } from 'src/app/services/usertype.service';
import { flattenDiagnosticMessageText } from 'typescript';
import { MainNavigationComponent } from '../main-navigation/main-navigation.component';

@Component({
  selector: 'app-home',
  templateUrl: './home.component.html',
  styleUrls: ['./home.component.scss']
})
export class HomeComponent implements OnInit {

  //variables to determine what content is shown in the home component
  showWarningChanges = true
  showWarningShifts = false
  showStatistics = true

  title = ''
  warningChanges = ''
  warningShifts = ''
  wordFrom = ''
  wordTo = ''
  labelContact = ''
  labelColleagues = ''
  labelTasks = ''
  labelStatistics = ''
  labelWorkingHours = ''
  labelWorkingDays = ''
  labelSickDays = ''
  labelVacationDays = ''
  wordDays = ''
  wordHours = ''
  wordClock = ''
  admin: boolean = false

  constructor(
    private translate: TranslateService, 
    private usertype: UsertypeService, 
    public dialog : MatDialog
    ) { }

  ngOnInit(): void {

    //display spinner
    this.dialog.open(SpinnerComponent, {
      id: 'Home_spinnerTranslationGlobal',
      autoFocus: false,
      disableClose: true
    });

    this.translate.getTranslation(this.translate.defaultLang).subscribe((translation: any) => { 

      //close spinner
      this.dialog.getDialogById('Home_spinnerTranslationGlobal')?.close();

      this.title = translation.Toolbar.Title.Home;
      this.warningChanges = translation.Home.WarningChanges;
      this.warningShifts = translation.Home.WarningShifts;
      this.wordFrom = translation.Home.WordFrom;
      this.wordTo = translation.Home.WordTo;
      this.labelContact = translation.Home.LabelContact;
      this.labelColleagues = translation.Home.LabelColleagues;
      this.labelTasks = translation.Home.LabelTasks;
      this.labelStatistics = translation.Home.LabelStatistics;
      this.labelWorkingHours = translation.Statistics.LabelWorkingHours;
      this.labelWorkingDays = translation.Statistics.LabelWorkingDays;
      this.labelSickDays = translation.Statistics.LabelSickDays;
      this.labelVacationDays = translation.Statistics.LabelVacationDays;
      this.wordDays = translation.Statistics.WordDays;
      this.wordHours = translation.Statistics.WordHours;
      this.wordClock = translation.WordClock;
    });
    this.checkBtn();

    //close spinner
    this.dialog.getDialogById('Home_spinnerCall')?.close();
  }

  ngAfterViewInit(): void{
    let toolbar =  document.getElementsByTagName('mat-toolbar')[0].clientHeight
    let bottomBar = document.getElementsByTagName('app-bottom-bar')[0].clientHeight
    var scrollView = document.getElementById('homeScrollView')

    let screenHeight = window.innerHeight-toolbar-bottomBar
    scrollView!.style.height = screenHeight + 'px'
  }

  private async checkBtn() {

    //display spinner
    this.dialog.open(SpinnerComponent, {
      id: 'Home_spinnerButtonCheck',
      autoFocus: false,
      disableClose: true
    });

    this.admin = (await this.usertype.getShown()).admin;
    
    //close spinner
    this.dialog.getDialogById('Home_spinnerButtonCheck')?.close();
  }
}