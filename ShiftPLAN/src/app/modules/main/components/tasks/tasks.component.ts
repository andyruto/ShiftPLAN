/**
 * tasks.component.ts
 * 
 *  Main typescript class for the tasks component.
 * 
 * author: Anne Naumann
 * last edit / by: 2021-06-12 / Anne Naumann
 */
import { Component, OnInit, AfterViewInit } from '@angular/core';
import { TranslateService } from '@ngx-translate/core';
import { Router } from '@angular/router'
import { UsertypeService } from 'src/app/services/usertype.service';

@Component({
  selector: 'app-tasks',
  templateUrl: './tasks.component.html',
  styleUrls: ['./tasks.component.scss']
})
export class TasksComponent implements OnInit, AfterViewInit {

  title = ''
  searchBarText = ''
  admin: boolean = false

  //test content that needs to be replaced
  tasks = [
    {name: 'Empfang_01', type: 'wiederholend'},
    {name: 'Empfang_02', type: 'wiederholend'},
    {name: 'Backup', type: 'wiederholend'},
    {name: 'KickOff_Juni', type: 'einmalig'},
    {name: 'Level1_Sup_01', type: 'wiederholend'},
    {name: 'Level1_Sup_02', type: 'wiederholend'},
    {name: 'HR_Schulung', type: 'einmalig'},
    {name: 'Inventur', type: 'wiederholend'},
    {name: 'Level2_Sup_01', type: 'wiederholend'},
    {name: 'Level2_Sup_02', type: 'wiederholend'},
    {name: 'Level2_Sup_03', type: 'wiederholend'},
    {name: 'Meeting_Praktikant', type: 'einmalig'},
    {name: 'Illinois', type: 'Springfield'}
  ]

  constructor(
    private translate: TranslateService, 
    private router : Router,
    private usertype : UsertypeService) { }

  ngOnInit(): void {
    this.translate.getTranslation(this.translate.defaultLang).subscribe((translation: any) => { 
      this.title = translation.Toolbar.Title.Tasks;
      this.searchBarText = translation.Tasks.SearchBarText;
    });

    this.checkBtn();
  }

  ngAfterViewInit(): void{
    let toolbar =  document.getElementsByTagName('mat-toolbar')[0]
    let bottomBar = document.getElementsByTagName('app-bottom-bar')[0]
    let searchBar = document.getElementsByTagName('mat-form-field')[0]
    var scrollView = document.getElementById('tasksScrollView')

    let screenHeight = window.innerHeight-toolbar.clientHeight-bottomBar.clientHeight-searchBar.clientHeight
    scrollView!.style.height = screenHeight + 'px'
  }

  private async checkBtn() {
    this.admin = (await this.usertype.getShown()).admin;
  }

  navigateToTask(): void{
    this.router.navigate(['/app/tasks-task'])
  }

  navigateToAddTask(): void{
    this.router.navigate(['/app/tasks-add'])
  }
}
