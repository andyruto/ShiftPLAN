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
import { Router } from '@angular/router'

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
  users: Array<string> = ['ag167','sw210','mt098','an055','mm123','ee456','wr246','ht135','os321','lp654','re642','tu531','lk133']

  constructor(private translate: TranslateService, private router : Router) {}

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

  navigateToUser(): void{
    this.router.navigate(['/app/admin-user'])
  }

  navigateToAddUser(): void{
    this.router.navigate(['/app/admin-add'])
  }
}
