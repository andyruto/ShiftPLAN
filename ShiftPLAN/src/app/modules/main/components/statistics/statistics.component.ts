/**
 * statistics.component.ts
 * 
 *  Main typescript class for the statistics component.
 * 
 * author: Anne Naumann
 * last edit / by: 2021-06-23 / Anne Naumann
 */
import { Component, OnInit, AfterViewChecked } from '@angular/core';
import { TranslateService } from '@ngx-translate/core';
import { SwiperConfigInterface } from 'ngx-swiper-wrapper';

@Component({
  selector: 'app-statistics',
  templateUrl: './statistics.component.html',
  styleUrls: ['./statistics.component.scss']
})
export class StatisticsComponent implements OnInit, AfterViewChecked {

  allStatisticData = [
    {month: 'März 2021', workHours: '67', workDays: '20', sickDays: '2', vacationDays: '3'},
    {month: 'April 2021', workHours: '67', workDays: '20', sickDays: '2', vacationDays: '3'},
    {month: 'Mai 2021', workHours: '67', workDays: '20', sickDays: '2', vacationDays: '3'},
    {month: 'Juni 2021', workHours: '67', workDays: '20', sickDays: '2', vacationDays: '3'}
  ]

  public config: SwiperConfigInterface = {
    direction: 'horizontal',
    slidesPerView: 1,
    navigation: true,
    initialSlide: this.allStatisticData.length
    //keyboard: true,
    //mousewheel: true,
  };

  title = ''
  labelWorkingHours = ''
  labelWorkingDays = ''
  labelSickDays = ''
  labelVacationDays = ''
  wordDays = ''
  wordHours = ''

  constructor(private translate: TranslateService) { }
  ngOnInit(): void {
    this.translate.getTranslation(this.translate.defaultLang).subscribe((translation: any) => { 
      this.title = translation.Toolbar.Title.Statistics;
      this.labelWorkingHours = translation.Statistics.LabelWorkingHours;
      this.labelWorkingDays = translation.Statistics.LabelWorkingDays;
      this.labelSickDays = translation.Statistics.LabelSickDays;
      this.labelVacationDays = translation.Statistics.LabelVacationDays;
      this.wordDays = translation.Statistics.WordDays;
      this.wordHours = translation.Statistics.WordHours;
    });
  }

  ngAfterViewChecked(): void{
    let toolbar =  document.getElementsByTagName('mat-toolbar')[0].clientHeight
    let bottomBar = document.getElementsByTagName('app-bottom-bar')[0].clientHeight
    var swiperContainer = document.getElementById('container')

    let screenHeight = window.innerHeight-toolbar-bottomBar
    swiperContainer!.style.height = screenHeight + 'px'
  }
}
