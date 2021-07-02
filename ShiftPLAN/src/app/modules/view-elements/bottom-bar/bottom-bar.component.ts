/**
 * bottom-bar.component.ts
 * 
 *  Main typescript class for the bottom-bar component.
 * 
 * author: Anne Naumann
 * last edit / by: 2021-05-29 / Anne Naumann
 */
import { Component, HostBinding, Input, OnInit} from '@angular/core';
import { MatDialog } from '@angular/material/dialog';
import { TranslateService } from '@ngx-translate/core';
import { SpinnerComponent } from '../spinner/spinner.component';

@Component({
  selector: 'app-bottom-bar',
  templateUrl: './bottom-bar.component.html',
  styleUrls: ['./bottom-bar.component.scss']
})
export class BottomBarComponent implements OnInit{

  home = ''
  shifts = ''
  tasks = ''
  applications = ''
  statistics = ''

  constructor(private translate : TranslateService, public dialog: MatDialog){}
  ngOnInit(): void {

    //display spinner
    this.dialog.open(SpinnerComponent, {
      id: 'spinnerTranslationGlobal',
      autoFocus: false,
      disableClose: true
    });

    this.translate.getTranslation(this.translate.defaultLang).subscribe((translation: any) => { 

      //close spinner
      this.dialog.getDialogById('spinnerTranslationGlobal')?.close();

      this.home = translation.BottomBar.Home;
      this.shifts = translation.BottomBar.Shifts;
      this.tasks = translation.BottomBar.Tasks;
      this.applications = translation.BottomBar.Applications;
      this.statistics = translation.BottomBar.Statistics;
    });
  }

  @Input() public tasksBtn: boolean = false
  @HostBinding('class.tasksAllowed')
  get taskGetter(){
    return this.tasksBtn
  }
  
  @Input() public applicationsBtn: boolean = false
  @HostBinding('class.applicationsAllowed')
  get applicationGetter(){
    return this.applicationsBtn
  }
}
