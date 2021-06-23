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

@Component({
  selector: 'app-tasks',
  templateUrl: './tasks.component.html',
  styleUrls: ['./tasks.component.scss']
})
export class TasksComponent implements OnInit, AfterViewInit {

  title = ''
  searchBarText = ''

  //test content that needs to be replaced
  tasks = [
    {name: 'Alabama', type: 'Montgomery'},
    {name: 'Alaska', type: 'Juneau'},
    {name: 'Arizona', type: 'Phoenix'},
    {name: 'Arkansas', type: 'Little Rock'},
    {name: 'California', type: 'Sacramento'},
    {name: 'Colorado', type: 'Denver'},
    {name: 'Connecticut', type: 'Hartford'},
    {name: 'Delaware', type: 'Dover'},
    {name: 'Florida', type: 'Tallahassee'},
    {name: 'Georgia', type: 'Atlanta'},
    {name: 'Hawaii', type: 'Honolulu'},
    {name: 'Idaho', type: 'Boise'},
    {name: 'Illinois', type: 'Springfield'}
  ]

  constructor(private translate: TranslateService, private router : Router) { }

  ngOnInit(): void {
    this.translate.getTranslation(this.translate.defaultLang).subscribe((translation: any) => { 
      this.title = translation.Toolbar.Title.Tasks;
      this.searchBarText = translation.Tasks.SearchBarText;
    });
  }

  ngAfterViewInit(): void{
    let toolbar =  document.getElementsByTagName('mat-toolbar')[0]
    let bottomBar = document.getElementsByTagName('app-bottom-bar')[0]
    let searchBar = document.getElementsByTagName('mat-form-field')[0]
    var scrollView = document.getElementById('tasksScrollView')

    let screenHeight = window.innerHeight-toolbar.clientHeight-bottomBar.clientHeight-searchBar.clientHeight
    scrollView!.style.height = screenHeight + 'px'
  }

  navigateToTask(): void{
    this.router.navigate(['/app/tasks-task'])
  }

  navigateToAddTask(): void{
    this.router.navigate(['/app/tasks-add'])
  }
}
