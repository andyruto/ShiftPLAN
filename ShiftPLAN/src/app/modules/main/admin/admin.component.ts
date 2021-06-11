/**
 * admin.component.ts
 * 
 *  Main typescript class for the admin component.
 * 
 * author: Anne Naumann
 * last edit / by: 2021-06-11 / Anne Naumann
 */
import { Component, OnInit, AfterViewInit } from '@angular/core';
import { TranslateService } from '@ngx-translate/core';

@Component({
  selector: 'app-admin',
  templateUrl: './admin.component.html',
  styleUrls: ['./admin.component.scss']
})
export class AdminComponent implements OnInit, AfterViewInit{

  title = ''
  tabUsers = ''
  labelKey = ''

  //test content that needs to be replaced
  users: Array<string> = ['a','b','c','d','e','f','g','h','i','b','c','d','e','f','g','h','i']

  constructor(private translate: TranslateService) {}

  ngOnInit(): void {
    this.translate.getTranslation(this.translate.defaultLang).subscribe((translation: any) => { 
      this.title = translation.Toolbar.Title.Admin;
      this.tabUsers = translation.Admin.TabUser;
      this.labelKey = translation.Admin.LabelApiKey;
    });
  }

  ngAfterViewInit(): void{
    let toolbar =  document.getElementsByTagName('mat-toolbar')[0]
    let bottomBar = document.getElementsByTagName('app-bottom-bar')[0]
    let header = document.getElementsByTagName('mat-tab-header')[0]
    var scrollView = document.getElementById('userScrollView')

    let screenHeight = window.innerHeight-toolbar.clientHeight-bottomBar.clientHeight-header.clientHeight
    scrollView!.style.height = screenHeight + 'px'
  }
}
