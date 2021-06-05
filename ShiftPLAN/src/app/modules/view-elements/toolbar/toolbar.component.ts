/**
 * toolbar.component.ts
 * 
 *  Main typescript class for the toolbar component.
 * 
 * author: Anne Naumann
 * last edit / by: 2021-06-05 / Anne Naumann
 */
import { Component, HostBinding, Input, OnInit } from '@angular/core';
import { TranslateService } from '@ngx-translate/core';
import { Location } from '@angular/common'
import { Router } from '@angular/router'

@Component({
  selector: 'app-toolbar',
  templateUrl: './toolbar.component.html',
  styleUrls: ['./toolbar.component.scss']
})

export class ToolbarComponent implements OnInit{

  profile = ''
  settings = ''
  admin = ''

  constructor(private translate : TranslateService, private location: Location, private router : Router){}
  ngOnInit(): void {
    this.translate.getTranslation(this.translate.defaultLang).subscribe((translation: any) => { 
      this.profile = translation.Toolbar.Profile;
      this.settings = translation.Toolbar.Settings;
      this.admin = translation.Toolbar.Admin;
    });
  }

  @Input() public title: string = "Initial"

  @Input() public backBtn: boolean = false
  @HostBinding('class.backActive')
  get backGetter(){
    return this.backBtn
  }

  @Input() public profileBtn: boolean = false
  @HostBinding('class.profileActive')
  get profileGetter(){
    return this.profileBtn
  }

  @Input() public settingsBtn: boolean = false
  @HostBinding('class.settingsActive')
  get settingsGetter(){
    return this.settingsBtn
  }

  @Input() public adminBtn: boolean = false
  @HostBinding('class.adminActive')
  get adminGetter(){
    return this.adminBtn
  }

  navigateBack(): void{
    this.location.back()
  }
  navigateToProfile(): void{
    this.router.navigate(['/app/main/profile'])
  }
  navigateToSettings(): void{
    this.router.navigate(['/app/main/settings'])
  }
  navigateToAdmin(): void{
    this.router.navigate(['/app/main/admin'])
  }
}
