/**
 * settings-password.component.spec
 * 
 * Typescript class containing the tests for the 
 * settings-password component.
 * 
 * author: Anne Naumann
 * last edit / by: 2021-06-07 / Anne Naumann
 */
import { ComponentFixture, TestBed } from '@angular/core/testing';
import { SettingsPasswordComponent } from './settings-password.component';

describe('SettingsPasswordComponent', () => {
  let component: SettingsPasswordComponent;
  let fixture: ComponentFixture<SettingsPasswordComponent>;

  beforeEach(async () => {
    await TestBed.configureTestingModule({
      declarations: [ SettingsPasswordComponent ]
    })
    .compileComponents();
  });

  beforeEach(() => {
    fixture = TestBed.createComponent(SettingsPasswordComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
