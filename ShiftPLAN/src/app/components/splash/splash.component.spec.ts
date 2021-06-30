/**
 * splash.component.spec.ts 
 * Typescript class containing the tests for the 
 * splash component.
 * 
 * author: Sascha W.
 * last edit / by: 2021-06-17 / Sascha W.
 */

//imports:
import { ComponentFixture, TestBed } from '@angular/core/testing';
import { SplashComponent } from './splash.component';

describe('SplashComponent', () => {
  let component: SplashComponent;
  let fixture: ComponentFixture<SplashComponent>;

  beforeEach(async () => {
    await TestBed.configureTestingModule({
      declarations: [ SplashComponent ]
    })
    .compileComponents();
  });

  beforeEach(() => {
    fixture = TestBed.createComponent(SplashComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});