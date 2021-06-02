/**
 * login.component.ts
 * 
 * Main typescript class for the login component.
 * 
 * author: Anne Naumann
 * last edit / by: 2021-06-02 / Anne Naumann
 */
import { Component, OnInit } from '@angular/core';
import { TranslateService } from '@ngx-translate/core';

@Component({
  selector: 'app-login',
  templateUrl: './login.component.html',
  styleUrls: ['./login.component.scss']
})
export class LoginComponent implements OnInit {

  userName = ''
  password = ''
  resetBtn = ''
  warning = ''

  constructor(private translate : TranslateService) { }
  ngOnInit(): void {
    this.translate.getTranslation(this.translate.defaultLang).subscribe((translation: any) => { 
      this.userName = translation.Login.UserName;
      this.password = translation.Login.Password;
      this.resetBtn = translation.Login.ResetButton;
      this.warning = translation.Login.Warning;
    });
  }

  showPassword(): void{
    //hide or show password
  }

  checkLoginValues(inputName: string, inputPassword: string): void{
    var warning = document.getElementById('warning')
    //TODO: get values
    let userName = ''
    let userPassword = ''

    if(userName == inputName && userPassword == inputPassword){
      //TODO: navigate to App
    }else {
      warning!.style.visibility = 'visible'
    }
  }

  resetPassword(): void{
    //TODO: implement function to reset password
  }
}