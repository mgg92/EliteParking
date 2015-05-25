package com.example.eliteparking.tests;

import android.app.Application;
import android.test.ApplicationTestCase;

public class ApplicationTest extends ApplicationTestCase<Application>{

    public ApplicationTest(){super(Application.class);}

    public void test3(){

        assertTrue(5 > 1);
    }

    public void test4(){


        assertTrue(5 < 1);

    }

}
