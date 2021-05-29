/**
 * bottom-bar.component.ts
 * 
 *  Main typescript class for the bottom-bar component.
 * 
 * author: Anne Naumann
 * last edit / by: 2021-05-29 / Anne Naumann
 */
import { Component, HostBinding, Input, OnInit} from '@angular/core';
import { TranslateService } from '@ngx-translate/core';

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

  constructor(private translate : TranslateService){}
  ngOnInit(): void {
    this.translate.getTranslation(this.translate.defaultLang).subscribe((translation: any) => { 
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
