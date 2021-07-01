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
import { UsertypeService } from 'src/app/services/usertype.service';

@Component({
  selector: 'app-shifts',
  templateUrl: './shifts.component.html',
  styleUrls: ['./shifts.component.scss']
})
export class ShiftsComponent implements OnInit, AfterViewInit {

  shifts = [
    {weekday: 'Montag', date: '05.07', start: '08:00', end: '14:00', task: 'Empfang_01', 
      employees : ['ag167', 'an055', 'sw210']},
    {weekday: 'Dienstag', date: '06.07', start: '14:00', end: '20:00', task: 'Empfang_02', 
      employees : ['ag167', 'sw210']},
    {weekday: 'Mittwoch', date: '07.07', start: '08:00', end: '09:00', task: 'Backup', 
      employees : ['mt098', 'ag167']},
    {weekday: 'Mittwoch', date: '07.07', start: '09:00', end: '14:00', task: 'Level1_Sup_01', 
    employees : ['ag167']},
    {weekday: 'Donnerstag', date: '08.07', start: '08:00', end: '14:00', task: 'Empfang_01', 
      employees : ['ag167', 'an055', 'sw210']},
    {weekday: 'Freitag', date: '09.07', start: '14:00', end: '20:00', task: 'Empfang_02', 
    employees : ['ag167', 'sw210']},
  ]

  title = ''
  wordClock = ''
  admin: boolean = false

  constructor(
    private translate: TranslateService, 
    private router : Router,
    private usertype : UsertypeService) { }

  ngOnInit(): void {
    this.translate.getTranslation(this.translate.defaultLang).subscribe((translation: any) => { 
      this.title = translation.Toolbar.Title.Shifts;
      this.wordClock = translation.WordClock
    });

    this.checkBtn();
  }

  ngAfterViewInit(): void{
    let toolbar =  document.getElementsByTagName('mat-toolbar')[0].clientHeight
    let bottomBar = document.getElementsByTagName('app-bottom-bar')[0].clientHeight
    var scrollView = document.getElementById('shiftsScrollView')

    let screenHeight = window.innerHeight-toolbar-bottomBar
    scrollView!.style.height = screenHeight + 'px'
  }

  private async checkBtn() {
    this.admin = (await this.usertype.getShown()).admin;
  }

  navigateToShift(){
    this.router.navigate(['/app/shifts-shift'])
  }

  navigateToAddShift(){
    this.router.navigate(['/app/shifts-add'])
  }
}
