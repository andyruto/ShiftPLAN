/**
 * toolbar.component.ts
 * 
 *  Main typescript class for the toolbar component.
 * 
 * author: Anne Naumann
 * last edit / by: 2021-05-29 / Anne Naumann
 */
import { Component, HostBinding, Input, OnInit } from '@angular/core';
import { TranslateService } from '@ngx-translate/core';
import { Location } from '@angular/common'

@Component({
  selector: 'app-toolbar',
  templateUrl: './toolbar.component.html',
  styleUrls: ['./toolbar.component.scss']
})

export class ToolbarComponent implements OnInit{

  profile = ''
  settings = ''
  admin = ''

  constructor(private translate : TranslateService, private location: Location){}
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
}
