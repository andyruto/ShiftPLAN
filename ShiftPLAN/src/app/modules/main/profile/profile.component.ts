/**
 * profile.component.ts
 * 
 *  Main typescript class for the profile component.
 * 
 * author: Anne Naumann
 * last edit / by: 2021-06-07 / Anne Naumann
 */
import { Component, OnInit } from '@angular/core';
import { TranslateService } from '@ngx-translate/core';

@Component({
  selector: 'app-profile',
  templateUrl: './profile.component.html',
  styleUrls: ['./profile.component.scss']
})
export class ProfileComponent implements OnInit {

  title = ''

  constructor(private translate: TranslateService) { }
  ngOnInit(): void {
    this.translate.getTranslation(this.translate.defaultLang).subscribe((translation: any) => { 
      this.title = translation.Toolbar.Title.Profile;
    });
  }

  checkLogout(): void{
    let checkDiv = document.getElementById('checkLogout')
    checkDiv!.style.display = 'block'
  }
  
  stopLogout(): void{
    let checkDiv = document.getElementById('checkLogout')
    checkDiv!.style.display = 'none'
  }

  userLogout(): void{
    console.log("User logs out")
  }
}
