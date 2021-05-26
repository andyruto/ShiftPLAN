/**
 * toolbar.component.ts
 * 
 *  Main typescript class for the toolbar component.
 * 
 * author: Anne Naumann
 * last edit / by: 2021-05-26 / Anne Naumann
 */
import { Component, HostBinding, Input } from '@angular/core';
import { Router } from '@angular/router'

@Component({
  selector: 'app-toolbar',
  templateUrl: './toolbar.component.html',
  styleUrls: ['./toolbar.component.scss']
})

export class ToolbarComponent{

  constructor(private router: Router){}

  @Input() public title: string = "Initial"

  //TODO: need to set default value to home-component
  @Input() public backUrl: string = ""

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
    this.router.navigate(['/', this.backUrl])
  }
}
