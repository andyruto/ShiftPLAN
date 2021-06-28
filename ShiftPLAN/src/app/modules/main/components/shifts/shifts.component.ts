/**
 * shifts.component.ts
 * 
 *  Main typescript class for the shifts component.
 * 
 * author: Anne Naumann
 * last edit / by: 2021-06-28 / Anne Naumann
 */
import { Component, OnInit, AfterViewInit } from '@angular/core';
import { TranslateService } from '@ngx-translate/core';
import { Router } from '@angular/router'

@Component({
  selector: 'app-shifts',
  templateUrl: './shifts.component.html',
  styleUrls: ['./shifts.component.scss']
})
export class ShiftsComponent implements OnInit, AfterViewInit {

  shifts = [
    {weekday: 'Freitag', date: '25.06', start: '10:00', end: '13:00', task: 'Logistik', 
      employees : ['Anne Naumann', 'Sascha Wirtz', 'Anne Naumann', 'Sascha Wirtz']},
    {weekday: 'Freitag', date: '25.06', start: '10:00', end: '13:00', task: 'Logistik', 
      employees : ['Anne Naumann', 'Sascha Wirtz']},
    {weekday: 'Freitag', date: '25.06', start: '10:00', end: '13:00', task: 'Logistik', 
      employees : ['Anne Naumann', 'Sascha Wirtz']}
  ]

  title = ''
  wordClock = ''

  constructor(private translate: TranslateService, private router : Router) { }
  ngOnInit(): void {
    this.translate.getTranslation(this.translate.defaultLang).subscribe((translation: any) => { 
      this.title = translation.Toolbar.Title.Shifts;
      this.wordClock = translation.WordClock
    });
  }

  ngAfterViewInit(): void{
    let toolbar =  document.getElementsByTagName('mat-toolbar')[0].clientHeight
    let bottomBar = document.getElementsByTagName('app-bottom-bar')[0].clientHeight
    var scrollView = document.getElementById('shiftsScrollView')

    let screenHeight = window.innerHeight-toolbar-bottomBar
    scrollView!.style.height = screenHeight + 'px'
  }

  navigateToShift(){
    this.router.navigate(['/app/shifts-shift'])
  }

  navigateToAddShift(){
    this.router.navigate(['/app/shifts-add'])
  }
}
